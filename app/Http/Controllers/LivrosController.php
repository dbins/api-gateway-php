<?php

namespace App\Http\Controllers;

use App\Services\LivroService;
use App\Traits\ApiResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class LivrosController extends Controller
{
    use ApiResponder;

    public $bookService;
    public $authorService;
    private $token;

    /**
     * Create a new controller instance.
     *
     * @param BookService   $LivroService
     */
    public function __construct(LivroService $livroService, Request $request)
    {
        $this->livroService = $livroService;
        $this->token = $request->bearerToken();
    }

    public function index()
    {
        //var_dump($request->headers->all());
        //$token = $request->bearerToken();
        //var_dump($token);
        return $this->successResponse($this->livroService->index());
    }

    public function store(Request $request)
    {
        return $this->successResponse($this->livroService->store($request->all()));
    }

    public function show($id)
    {
        return $this->successResponse($this->livroService->show($id));
    }

    public function update(Request $request, $id)
    {
        return $this->successResponse($this->livroService->update(($request->all()),
        $id));
    }

    public function destroy($id)
    {
        return $this->successResponse($this->livroService->destroy($id));
    }

	 public function contact(Request $request)
    {
        return $this->successResponse($this->livroService->contact(($request->all())));
    }

	 public function upload(Request $request, $id)
    {
        return $this->successResponse($this->livroService->upload(($request->all()),$id));
    }

    public function export($formato)
    {
        return $this->successResponse($this->livroService->export($formato));
    }

	 public function doc()
    {
        return $this->successResponse($this->livroService->doc());
    }
}
