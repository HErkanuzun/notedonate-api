<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckResourceOwnership
{
    public function handle(Request $request, Closure $next): Response
    {
        $resource = $request->route()->parameters();
        
        // Route'dan gelen model instance'ını al
        $model = reset($resource);
        
        if (!$model) {
            return $next($request);
        }

        // Modelin created_by alanı mevcut kullanıcıya ait değilse
        if ($model->created_by !== auth()->id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not authorized to access this resource'
            ], 403);
        }

        return $next($request);
    }
}
