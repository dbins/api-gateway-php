<?php

namespace App\Services;

use App\Traits\ConsumeExternalService;
use Firebase\JWT\JWT;
use App\Models\User;
use Illuminate\Http\Request;

class LivroService {
    use ConsumeExternalService;

    public $baseUri;
    /**
     * @var \Laravel\Lumen\Application|mixed
     */
    private $secret;
    private $token;
    private $user;

    public function __construct(Request $request)
    {
        $this->baseUri = env('SERVICES_LIVROS_BASE_URI', '');
        $this->secret = env('SERVICES_LIVROS_SECRET', '');
        $this->token = $this->jwt($request->user());
        //$this->user = $request->user()->id ?? 0;
        //var_dump($request->user()->email);

    }

    protected function jwt(User $user) {
        $dados = [
            'user'=>  $user->id ?? 0,
            'nome'=>  $user->nome ?? '',
            'email'=>  $user->email ?? ''
        ];
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $dados, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('SERVICES_LIVROS_SECRET'));
    }


    public function index(){

        return $this->performRequest('GET','/api/v1/livros');
    }
    public function show($livro){
        return $this->performRequest('GET',"/api/v1/livros/{$livro}");
    }
    public function store($data) {
        return $this->performRequest('POST','/api/v1/livros',$data);
    }
    public function update($data, $livro) {
        return $this->performRequest('PUT',"/api/v1/livros/{$livro}",$data);
    }
    public function destroy($livro) {
        return $this->performRequest('DELETE',"/api/v1/livros/{$livro}");
    }
	public function contact($data) {
        return $this->performRequest('POST',"/api/v1/livros/contato",$data);
    }
	public function upload($data, $livro) {
        return $this->performRequest('POST',"/api/v1/livros/upload/{$livro}",$data);
    }
    public function export($formato) {
        return $this->performRequest('GET',"/api/v1/livros/exportar/{$formato}");
    }
	public function doc() {
        return $this->performRequest('GET',"/api/doc");
    }
}
