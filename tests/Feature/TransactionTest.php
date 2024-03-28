<?php

namespace Tests\Feature;

use App\Models\Machine;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;


class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function testTransactionLifecycle()
    {
        $this->initializeTransaction();

        $this->assertTransactionIsPending();

        $this->processTransactionPayment();

        $this->assertTransactionIsApproved();
    }

    private function initializeTransaction()
    {
        // fake mercado pago transaction creation
        Http::fake([
            'https://api.mercadopago.com/v1/payments' => Http::response(
                [
                    "id" => 456456456,
                    "point_of_interaction" => [
                        "transaction_data" => [
                            "qr_code" => "00020126580014br.gov.bcb.pix0136470325c4-4e90-4b8e-81ac-cad520d36c6e520400005303986540510.095802BR5922BRENODONASCIMENTOSILVA6013So Jos dos Ca62240520mpqrinter749085062636304FA2C",
                            "ticket_url" => "https://www.mercadopago.com.br/payments/456456456/ticket",
                            "qr_code_base64" => "iVBORw0KGgoAAAANSUhEUgAABWQAAAVkAQAAAAB79iscAAAN6ElEQVR4Xu3XUZIkuwmF4dyB979L76Ac5kCCQNkOO1r3Vo7\/81AjpQB96re5Pi\/KP6\/+5ZuD9lzQngvac0F7LmjPBe25oD0XtOeC9lzQngvac0F7LmjPBe25oD0XtOeC9lzQngvac0F7LmjPBe25oD0XtOeC9lzQngvac0F7LmjPBe25oD0XtOeC9lzQngvac0F7LmjPBe25oD2Xqr16\/qGKy35smwf11Hrbt+zIn+s+yLSDvCNjQy1o0Spo0Spo0Spo0Spo0Spo0Spv1ub32Fp5RbU0XqRNScqu2NLeNwZY0F5oLWgvtBa0F1oL2gutBe2F1oL2Qmt5udabl656YIMteWPWxWkz1rasiwFt1CMDbXagrUMeytCinQcXWrQR\/4oWrYIWrYIWrfKF2pjiscGp8G307k8bJab4Ku9tP9mBFi3au3d\/ija7ljLfRvae6N2fos2upcy3kb0nevenaLNrKfNtZO+J3v0p2uxaynwb2Xuid3+KNruWMt9G9p7o3Z+iza6lzLeRvSd696dos2sp821k74ne\/elfrfVTW7V78ttP7xvQ3Vvs9IHhQbsE7WNZHWIrtHceGR60S9A+ltUhtkJ755HhQbsE7WNZHWIrtHceGR60S9A+ltUhtvou7djaTEussq4ao3icZuq1glbt0pZBi3Zu0aJV0KJV0KJV0KJV0KJV3qxtWSh\/4c9koP2tn8lA+1s\/k4H2t34mA+1v\/UwG2t\/6mQy0v\/UzGWh\/62cy0P7Wz2Sg\/a2fyXi9dpes9dXu\/3\/X\/d\/LZXpTeEduLfO\/pj8ELVoFLVoFLVoFLVoFLVoFLVrlzdqmsMG5Wq5td9fizDJg\/77HoVYSb\/GgzaCtO7S+QotWK7RotUKLViu0aLVC+yKtfyplPslWcUXOzBInzGu9JNNe1Uom3s8taNEqaNEqaNEqaNEqaNEqaNEq79XuAdd995L6INvmFEuMym\/1gyUe1ObV57ZRaC1o0Spo0Spo0Spo0Spo0SpoX6y9P0VFGxfJIbnyg+Xu8WbLohjj258lSu7Te5mfVIb2gxbt9KBFq6BFq6BFq6BFq6D9Vm2tiGT\/uCdXy9Ns5RMbeZlcvw1UP70P7uV2JtolaJfVAKBdT++De7mdiXYJ2mU1AGjX0\/vgXm5nol2CdlkNANr19D64l9uZaJegXVYD8Ldo70+KbbK29efdzgvjeG4kt01WT+f7atCijeJ1b58U26BF2wdnP1q0MQntPQotWrRo0d6TvkGbDdnlB7ZavuXBD3XL3V6Sd1h2L13esm7vJVpf+WQryTssaNEqaNEqaNEqaNEqaNEqaL9Xa6sF5cmZeePybef2D7nK01hVo\/Fi\/GCgtS1atNqiRastWrTaokWrLVq02qJ9sdbirUuqbPnxZEdOz2cslDytvfmz+1aH3ksFLVoFLVoFLVoFLVoFLVoFLVrlNdr8\/njt0I5xD8aE2ulVnzva8jQnW9BG8eiIoPWgRaugRaugRaugRaugRat8rzbi27gxDzJVa0lZjKqvyg5bzSszfhTF6x\/jXqJF62kNaGM1r8z4EVq0Clq0Clq0Clq0Ctq\/U7vImmfcbR2xqqfLC\/IZ49tsa0+rB158L9Gi9aBFq6BFq6BFq6BFq6BFq7xNG2XDmCXLPXm37lBHnN6VS0lOztPWtvygtWLviNO7Ei1aD1rvQotWXWjRqgstWnWhRauul2gz2e+11mVp22vDe3hue9p47vKTHTVo0Spo0Spo0Spo0Spo0Spo0Srv1dbDduNSMgYHvq5iSl21B+06Insy2uxAG0GLdvf9QovWvl9o0dr3Cy1a+36hRWvfry\/XWrw5+8Pdtm1c3VoetslrbfvTa30GWsvDdnjQokWLNr+hHZeh3Z5eaNGi\/aDN0wvt36Gth9HVVv5jxY\/GWeIDom6Mz5JFO4J2lviAqBvj0aJFe692lOxFuwTtLPEBUTfGo0WL9l7tKNmLdgnaWeIDom6M\/3\/QtunJC4AnPVY3D+o2fvZP+3k75uUaLVoFLVoFLVoFLVoFLVoFLVrlXdpMNjx1BeC6D9qrZomvbdvm2Wle1EZl0D6U+Nq2k4LWMynjilnia9tOClrPpIwrZomvbTspaD2TMq6YJb627aSg9UzKuGKW+Nq2k4LWMynjilnia9tOClrPpIwrZomvbTspaD2TMq6YJb627aSg9UzKuGKW+Nq2k\/KHa3Ncm+nuPPDWkrZt33bu8dzlwE\/z72VBO7+h9eYIWrQKWrQKWrQKWrQKWrTKG7S5yqR7d5rj2qvq1koeXj+2LWjt4FGB9kKbJWhjlUH7ULcLWjt4VKC90GYJ2lhl0D7U7YLWDh4VaK0rfvxba7Dktcvp6A3jbt7+trmqQYtWQYtWQYtWQYtWQYtWQYtWebk2p49xi7Gt9m1Zt3M\/fNv9eNDmT9Y9yHbfdj8etPmTdQ+y3bfdjwdt\/mTdg2z3bffjQZs\/Wfcg233b\/XjQ5k\/WPch233Y\/HrT5k3UPst233Y8Hbf5k3YNs923340GbP1n3INt92\/140OZP1j3Idt92P573au9PS3+kepZk2653PHJXYqtFVo0ZtGgVtGgVtGgVtGgVtGgVtGiVl2utv8WviJVXxjcfnNpMAP7T6+dpHZpBm0G7pDZE0KJV0KJV0KJV0KJV0KJVvlQbN7afTPb7NtpyugMsqY2t9+6eEatWUoMWrYIWrYIWrYIWrYIWrYIWrfJmrX8qRvtSZ0ayvg6e2lqSbVk3yY9btGgjaNEqaNEqaNEqaNEqaNEq79XW\/hjsq2V6fdDcWseO4nVz6JgX8boMWgtatApatApatApatApatAraV2vj0Faedm3euAwe2pbotVi9r+LbOIihvvJtrluDB+2\/4yu0aLVCi1YrtGi1QotWK7RotUL7hdr7U7RabSqWG73kc79l6WhTaol1WNq8eH29LbYetGgVtGgVtGgVtGgVtGgVtGiV92rX72VIHbfgx8+SSo6h\/vNZX5+8pcPXaNEqaNEqaNEqaNEqaNEqaNEqf4LWktfWrzGzlszL8tvdWEb5anq8cndHnnpJ3X2ey+YktP2OPPWSuvs8l81JaPsdeeoldfd5LpuT0PY78tRL6u7zXDYnoe135KmX1N3nuWxOQtvvyFMvqbvPc9mchLbfkadeUnef57I5CW2\/I0+9pO4+z2VzEtp+R556Sd19nsvmJLT9jjz1krr7PJfNSd+gzXGe1pWUBs2S3WmWLAd1GwPG6zNod6do\/dMStGgVtGgVtGgVtGgVtGiV79W21gfZfmt5+Jaj\/nt3Ddr5De1oQIv2HjcO0PahdhrFNWjnN7SjAS3ae9w4QNuH2mkU16Cd39Bma1x0d1mydXnG\/bUkO9qNbWgafVRuo2S9916iRXsfokWrQ7RodYgWrQ7RotUhWrQ6fJM2786Zj\/11tbwvD3LA6G0vWLR+5XW3oW1Bi\/Y+yAGjF218Q1uCNg\/QRh1atGjLjblC++\/UUwvaOMgBo\/cXtPWeXcPi9gQlTxNvn3dvaaia3d8BLVq09wotWq3QotUKLVqt0KLVCu0foq1p4x6HzBfcPUqbUkuWp41vy5s9aJegHUGLVkGLVkGLVkGLVkGLVnmJNit8FZ7c7ouT8vCgLK7fFu3+ICajRRtBizba7qXiXblCu0XZ6e4ALVoFLVoFLVoF7V+vtdqs2LVedabj4wV5mvPqKn7Gg1pxpJ5a0KJV0KJV0KJV0KJV0KJV0KJVXq9tkxolVq0uoe1pDfXzw\/Nbe5oHLVoFLVoFLVoFLVoFLVoFLVrlvdo2s\/U3fB2XV8xez\/KqCoiO2pavb0GLVkGLVkGLVkGLVkGLVkGLVnmv1hLj7yyTBi\/x2bug6ja+7duuOn7\/NLRoFbRoFbRoFbRoFbRoFbRolfdqa1nMzHhJO2iU666LlScf2U7TbSXzaWgzOSrm1o5xivZCGwe1BC1atP0AbQnaJTkq5taOcYr2QhsHtQQt2v9Re1WZb21m\/GR\/LVkGeOKyVpe9e6Nl6fWgzaCNZKVv0d5B61u0aLVFi1ZbtGi1RYtW2y\/U7lJrbXvVt7S06aOkPddWIbO0Vd7mQTvv9qBdghatghatghatghatghat8r3auzmSl8UV9SDurgCbEuSGGnWZXe88RYs2ghatghatghatghatghat8mZtfo9tvbFpH0vm6X1xL2nPbb0jaOept0Uv2j0lbvyhZJ56W\/Si3VPixh9K5qm3RS\/aPSVu\/KFknnpb9KLdU+LGH0rmqbdFL9o9JW78oWSeelv0ot1T4sYfSuapt0Uv2j0lbvyhZJ56W\/Si3VPixh9K5qm3Re\/\/q7bNtE0taaetY8YpuTJFGOvQlqhbn3Yv591o0aJFm7VoL7Ro0U7PhRYtWrSv1UZtvce2kfTUbXvpAn18aY73OxYBWrQRtGgVtGgVtGgVtGgVtGiVP0vrBzbJErI6eLnCsnuu1zVt\/gl20LB40H7QWtB+0FrQftBa0H7QWtB+0FrQftBaXq5t2\/qTiStyWw7XrQ9bPLXu4co23oMWrYIWrYIWrYIWrYIWrYIWrfJmbUt07VstdrA8KA\/s32yLCXXeePMC9QFo0WoAWrQagBatBqBFqwFo0WoAWrQa8Cdovz9ozwXtuaA9F7TngvZc0J4L2nNBey5ozwXtuaA9F7TngvZc0J4L2nNBey5ozwXtuaA9F7TngvZc0J4L2nNBey5ozwXtuaA9F7TngvZc0J4L2nNBey5ozwXtuaA9F7TngvZc0J4L2nN5mfZfe0CHO2GrHG4AAAAASUVORK5CYII="
                        ]
                    ]
                ],
                Response::HTTP_OK
            )
        ]);

        // create transaction resources
        $machine = Machine::factory()->create();
        $product = Product::factory()->create();

        $machine->products()->attach($product, ['quantity' => fake()->numberBetween(1, 100)]);

        $response = $this->post('api/transactions', [
            'machine_id' => $machine->id,
            'product_id' => $product->id,
            'price' => $product->price
        ]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson([
            'success' => true
        ]);

        $response->assertJsonStructure([
            'identifier',
            'success',
            'qr_code',
            'qr_code_link',
            'qr_code_base64'
        ]);
    }

    private function assertTransactionIsPending()
    {
        $response = $this->get('api/transactions');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'machine_product_id' => 1,
                    'type' => 'pix',
                    'status' => 'pending'
                ]
            ]
        ]);
    }

    public function processTransactionPayment()
    {
        // fake mercado pago webhook response
        Http::fake([
            'https://api.mercadopago.com/v1/payments/*' => Http::response(['status' => 'approved'], 200)
        ]);


        // process transaction
        $webhookResponse = $this->post(
            'api/webhook',
            [
                "action" => "payment.updated",
                "api_version" => "v1",
                "data" => ["id" => 456456456],
                "live_mode" => false,
                "type" => "payment",
            ]
        );

        $webhookResponse->assertJson([
            'success' => true
        ]);
    }

    private function assertTransactionIsApproved()
    {
        $response = $this->get('api/transactions');

        $response->assertStatus(Response::HTTP_OK);

        $response->assertJson([
            'data' => [
                [
                    'id' => 1,
                    'machine_product_id' => 1,
                    'type' => 'pix',
                    'status' => 'approved'
                ]
            ]
        ]);
    }
}
