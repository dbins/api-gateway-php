<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Services\LivroService;
use App\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{


     /**
     * @OA\Info(
     *     version="1.0",
     *     title="Gateway",
     *     description="Swagger do Gateway",
     *      @OA\Contact(
     *          email="admin@admin.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     * *     * @OA\Server(
     *      url="http://localhost:8001",
     *      description="Demo API Server"
     *      )
     *
     *     @OA\Tag(
     *     name="Gateway",
     *     description="API Endpoints - Gateway"
     *     )
     */

     /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * @OA\Post(
     *     tags={"gateway"},
     *     path="/auth/signup",
     *     summary="Criar um novo usuário",
     *     description="Criar um novo usuário",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="nome",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$",
     *                     format="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"nome": "a3fb6", "email": "teste@teste.com.br", "password": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *          @OA\JsonContent(
     *                  @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Usuário criado com sucesso"
     *                 )
     *          )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     */

    /**
     * Registro do usuario
     */
    public function signUp(Request $request)
    {
        $this->validate($this->request, [
            'nome' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();

        if ($user) {
            return response()->json([
                'error' => 'User already exist.'
            ], 400);
        }

        //Hash::make($this->request->input('password'))

        User::create([
            'nome' => $this->request->input('nome'),
            'email' => $this->request->input('email'),
            'password' =>Hash::make($this->request->input('password'))
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso!'
        ], 201);
    }

    /**
     * @OA\Post(
     *     tags={"gateway"},
     *     path="/auth/login",
     *     summary="Login do usuário",
     *     description="Login do usuário",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$",
     *                     format="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "teste@teste.com.br", "password": "123456"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *          @OA\JsonContent(
     *                  @OA\Property(
     *                     property="user",
     *                     type="object",
     *                     @OA\Property(
     *                          property="id",
     *                          type="integer"
     *                     ),
     *                     @OA\Property(
     *                          property="nome",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="created_at",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="updated_at",
     *                          type="string"
     *                      )
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="object",
     *                        @OA\Property(
     *                            property="token_type",
     *                            type="string",
     *                            example="Bearer"
     *                       ),
     *                       @OA\Property(
     *                             property="expires_in",
     *                             type="string",
     *                             example="31535999"
     *                       ),
     *                      @OA\Property(
     *                          property="access_token",
     *                          type="string",
     *                          example="eyJ0eXAiOiJKV1QiLC"
     *                      ),
     *                      @OA\Property(
     *                          property="refresh_token",
     *                          type="string",
     *                          example="eyJ0eXAiOiJKV1QiLC"
     *                      )
     *
     *                 )
     *          )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="E-mail não existe"
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Senha inválida"
     *     )
     * )
     */
    /**
     * Criar o token
     */
    public function login(Request $request)
    {
        $this->validate($this->request, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'error' => 'E-mail não existe'
            ], 400);
        }

        if (!Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'error' => 'Senha inválida'
            ], 401);
        }

        $token = [];
        //O codigo abaixo apenas funciona em produção
        //Testando na máquina local não funciona porque é single thread
        $http = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8001',
            'timeout'  => 6.0,
            'connect_timeout' => 6.0,
            'read_timeout' => 6.0,
        ]);

        try{
        $response = $http->request('POST', '/oauth/token', [
            'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('CLIENT_ID', ''),
					'client_secret' => env('CLIENT_SECRET', ''),
                   'scope' => '*',
                    "password" => $this->request->input('password'),
                    "username" => $this->request->input('email')
                ],
        ]);
        $token = json_decode($response->getBody()->getContents());
    }
    catch(\GuzzleHttp\Exception\RequestException $e){
       // Aqui podem ser tratados erros do tipo 400 ou 500
       // Pode ser feito o log dos erros usando Illuminate\Support\Facades\Log;
       $error['error'] = $e->getMessage();
       $error['request'] = $e->getRequest();
       if($e->hasResponse()){
           if ($e->getResponse()->getStatusCode() == '400'){
               $error['response'] = $e->getResponse();
           }
       }
       Log::error('Houve um erro ao fazer esta requisição.', ['error' => $error]);
    }catch(Exception $e){
       //Outros tipos de erros
    }
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     tags={"gateway"},
     *     path="/auth/refresh",
     *     summary="Atualiza o token do usuário",
     *     description="Atualiza o token do usuário",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="token",
     *                     type="string"
     *                 )
     *                 example={"token": "a3fb6"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *          @OA\JsonContent(
     *                  @OA\Property(
     *                     property="token_type",
     *                     type="string",
     *                     example="Bearer"
     *                 ),
     *                 @OA\Property(
     *                     property="expires_in",
     *                     type="string",
     *                     example="31535999"
     *                 ),
     *                 @OA\Property(
     *                     property="access_token",
     *                     type="string",
     *                     example="eyJ0eXAiOiJKV1QiLC"
     *                 ),
     *                 @OA\Property(
     *                     property="refresh_token",
     *                     type="string",
     *                     example="eyJ0eXAiOiJKV1QiLC"
     *                 ),
     *          )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     */

	/**
     * Atualizar o token com o refresh token gerado no login
     */
    public function refresh(Request $request)
    {
        $this->validate($this->request, [
            'token' => 'required|string'
        ]);

        $token = [];
        //O codigo abaixo apenas funciona em produção
        //Testando na máquina local não funciona porque é single thread
        $http = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8001',
            'timeout'  => 6.0,
            'connect_timeout' => 6.0,
            'read_timeout' => 6.0,
        ]);

        try{
			$response = $http->request('POST', '/oauth/token', [
				'form_params' => [
					'grant_type' => 'refresh_token',
					'client_id' => env('CLIENT_ID', ''),
					'client_secret' => env('CLIENT_SECRET', ''),
					'scope' => '*',
					'refresh_token' => $this->request->input('token')
				],
			]);
			$token = json_decode($response->getBody()->getContents());
		}
		catch(\GuzzleHttp\Exception\RequestException $e){
		   // Aqui podem ser tratados erros do tipo 400 ou 500
		   // Pode ser feito o log dos erros usando Illuminate\Support\Facades\Log;
		   $error['error'] = $e->getMessage();
		   $error['request'] = $e->getRequest();
		   if($e->hasResponse()){
			   if ($e->getResponse()->getStatusCode() == '400'){
				   $error['response'] = $e->getResponse();
			   }
		   }
		   Log::error('Houve um erro ao fazer esta requisição.', ['error' => $error]);
		}catch(Exception $e){
		   //Outros tipos de erros
		}

        return response()->json([
            'token' => $token
        ]);
    }

        /**
     * @OA\Post(
     *     tags={"gateway"},
     *     path="/auth/logout",
     *     summary="Cancela o token do usuário",
     *     description="Cancela o token do usuário",
     *     security={ {"bearer": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *          @OA\JsonContent(
     *                  @OA\Property(
     *                     property="message",
     *                     type="string",
     *                     example="Usuário deslogado com sucesso"
     *                 )
     *          )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Missing Data"
     *     )
     * )
     */


    /**
     * Cancelar o token
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Usuário deslogado com sucesso'
        ]);
    }

    /**
    * @OA\Get(
    *     tags={"gateway"},
    *     summary="Retorna os dados do usuário",
    *     description="Retorna os dados do usuário",
    *     path="/auth/user",
    *     security={ {"bearer": {} }},
    *     @OA\Response(response="200", description="Dados do usuário autenticado",
    *     @OA\JsonContent(
    *
    *
    *                 @OA\Property(
    *                     property="nome",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="email",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="created_at",
    *                     type="string"
    *                 ),
    *                 @OA\Property(
    *                     property="updated_at",
    *                     type="string"
    *                 ),
    *                 example={"nome": "a3fb6", "email": "teste@teste.com.br", "created_at": "2020-12-25T19:17:18.000000Z", "updated_at":"2020-12-25T19:17:18.000000Z"}
    *
     *
     *     )
     *     ),
     *     @OA\Response(response=422, description="Faltam informações")
     * ),
     */

    /**
     * Retornar os dados do usuario que fez a requisição
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
