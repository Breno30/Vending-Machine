<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::all();
        return response()->json(['data' => $transactions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $productId = $request->product_id;
        $machineId = $request->machine_id;

        $product = Product::findOrFail($productId);
        $productPrice = $product->price;
        $requestPrice = $request->price;

        if ($productPrice != $requestPrice) {
            return response()->json([
                'success' => false,
                'error' => 'Product price does not match the requested price'
            ], 400);
        }

        $relation = DB::table('machine_product')
            ->where('machine_id', $machineId)
            ->where('product_id', $productId)
            ->first();

        if (!$relation) {
            return response()->json([
                'success' => false,
                'error' => 'Product not found in this machine'
            ], 400);
        }

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . env('MERCADO_PAGO_ACCESS_TOKEN')
        ])->post(
            'https://api.mercadopago.com/v1/payments',
            [
                "transaction_amount" => $request->price,
                "description" => $product->name,
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => "test@gmail.com"
                ]
            ]
        );

        $body = $response->getBody()->getContents();
        $data = json_decode($body);

        $identifier = $data->id;
        $qrCode = $data->point_of_interaction->transaction_data->qr_code;
        $qrCodeLink = $data->point_of_interaction->transaction_data->ticket_url;
        $qrCodeBase = $data->point_of_interaction->transaction_data->qr_code_base64;

        Transaction::create([
            'machine_product_id' => $relation->id,
            'type' => 'pix',
            'identifier' => $identifier,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'identifier' => $identifier,    
            'qr_code' => $qrCode,
            'qr_code_link' => $qrCodeLink,
            'qr_code_base64' => $qrCodeBase
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        return response()->json(['data' => $transaction]);
    }

    /**
     * Handle webhook from mercado pago.
     */
    public function handleWebhook(Request $request) {
        Log::info($request);

        if ($request->action != 'payment.updated') {
            return [
                'success' => false,
                'message' => 'unable to handle this action'
            ];
         }
    
        $identifier = $request->data['id'];
    
        $transaction = Transaction::where('identifier', $identifier)->first();

        if (!$transaction) {
            return [
                'success' => false,
                'message' => 'Transaction not found'
            ];
        }

        $transactionData = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . env('MERCADO_PAGO_ACCESS_TOKEN')
        ])->get("https://api.mercadopago.com/v1/payments/$identifier");

        $status = $transactionData['status'];

        $transaction->status = $status;
        $transaction->save();
    
        $success = $status == 'approved';

        if ($success) {
            $transaction->markAsPaid();
        }

        return [
            'success' => $status == 'approved'
        ];
    }
}
