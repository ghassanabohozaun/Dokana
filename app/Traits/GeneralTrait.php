<?php

namespace App\Traits;

use Image;

trait GeneralTrait
{
    // get Current Lang
    public function getCurrentLang()
    {
        return app()->getLocale();
    }
    // return Error
    public function returnError($msg = '', $errNum)
    {
        return response()->json([
            'status' => false,
            'errNum' => $errNum,
            'msg' => $msg,
        ]);
    }
    // return Success Message
    public function returnSuccessMessage($msg = '')
    {
        return [
            'status' => true,
            'errNum' => '',
            'msg' => $msg,
        ];
    }
    // return Data
    public function returnData($msg = '', $data)
    {
        return response()->json([
            'status' => true,
            'errNum' => '',
            'msg' => $msg,
            'data' => $data,
        ]);
    }
    // return Validation Error
    public function returnValidationError($code = '', $validator)
    {
        return $this->returnError($code, $validator->errors());
    }
    // return Code According To Input
    public function returnCodeAccordingToInput($validator)
    {
        $inputs = array_keys($validator->errors()->toArray());
        $code = $this->getErrorCode($inputs[0]);
        return $code;
    }

    // get Error Code
    public function getErrorCode($input)
    {
        if ($input == 'name_ar') {
            return 'E0011';
        } elseif ($input == 'password') {
            return 'E002';
        } else {
            return '';
        }
    }
    // save Image
    public function saveImage($name, $path)
    {
        $ImageExtenstion = $name->getClientOriginalExtension();
        $ImageName = time() . '.' . $ImageExtenstion;
        $name->move($path, $ImageName);
        return $ImageName;
    }

    // save File
    public function saveFile($name, $path)
    {
        $fileExtenstion = $name->getClientOriginalExtension();
        $fileName = time() . rand() . '.' . $fileExtenstion;
        $name->move($path, $fileName);
        return $fileName;
    }
}
