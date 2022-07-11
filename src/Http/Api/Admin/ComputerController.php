<?php

namespace MediaManager\Http\Api\Admin;

use MediaManager\Http\Requests\ComputerRegisterRequest;
use MediaManager\Http\Resources\ComputerCollection;
use MediaManager\Http\Resources\ComputerResource;
use MediaManager\Models\Computer;
// use MediaManager\Http\Requests\ComputerDeleteRequest;
// use MediaManager\Http\Requests\ComputerUserRequest;
// use MediaManager\Http\Requests\ComputerValidRequest;
// use MediaManager\Http\Requests\ComputerValidationRequest;
// use MediaManager\Http\Requests\ComputerValidateTokenRequest;
use MediaManager\Util\Filter;
use Illuminate\Support\Facades\Log;

class ComputerController extends Controller
{


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $computer = Computer::findOrFail($id);
        $computer->is_active = false;
        $computer->blocked_at = now();
        $computer->save();
        // $computer->delete();

        return response()->json(
            'Dispositivo foi deletado com sucesso'
        );
    }

}