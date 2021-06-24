<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RealStatePhoto;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId){
        try{

            $photo = $this->realStatePhoto
                        ->where('real_state_id', $realStateId)
                        ->where('is_thumb', true);
            if($photo->count()) $photo->first()->update(['is_thumb' => false]);

            $photo = $this->realStatePhoto->find($photoId);
            $photo->update(['is_thumb' => true]);
            return response()->json([
                'data'=>[
                    'msg'=> 'Thumb atualizado com sucesso!'
                ]
                ], 200);

        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 401);
        }
    }

    public function remove($photoId){
        try{



            $photo = $this->realStatePhoto->find($photoId);



            if($photo->is_thumb){
                return response()->json([
                    'data'=>[
                        'msg'=> 'Não é possivel remover imagens que seja thumb, atualize a imagem da thumb e remova a imagem desejada!'
                    ]
                    ], 200);

            }
            if($photo){
                Storage::disk('public')->delete($photo->photo);
                $photo->delete();
            }
            return response()->json([
                'data'=>[
                    'msg'=> 'Foto removida com sucesso!'
                ]
                ], 200);

        }catch (\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 401);
        }
    }
}
