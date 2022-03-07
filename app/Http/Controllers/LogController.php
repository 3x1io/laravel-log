<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

use function PHPSTORM_META\map;

class LogController extends Controller
{

    /**
     * @return Inertia Home Page
     * home index with log dir files
     */
    public function index()
    {
        //Get Files Inside Log Dir | We care here use valet and MacOS so log path is
        // /Users/fadymondy/.valet/log
        //Let's go inside this path

        $getPathFiles = File::files('/Users/fadymondy/.valet/log');

        //The File Array here is an object of file i will change it to another array response
        $response = [];

        foreach ($getPathFiles as $file) {
            array_push($response, [
                "name" => $file->getFilename(),
                "path" => $file->getPath() . '/' . $file->getFilename()
            ]);
        }

        //Now Return Insertia Response To Deal with it on VueJs
        return Inertia::render('Home', [
            "response" => $response
        ]);
    }

    /**
     * @return Response $response
     * @param Request $request
     *
     * return json response with data of lines of log
     */
    public function log(Request $request)
    {
        //Check if Request Has a vaild params
        $vaild = Validator::make($request->all(), [
            "file" => "required|string",
            "page" => "required"
        ]);

        if ($vaild->fails()) {
            return response()->json([
                "success" => false,
                "message" => $vaild->messages(),
                "body" => []
            ], 422);
        } else {

            //Check If Selected File Exists
            $checkFileExists = File::exists($request->get('file'));

            if ($checkFileExists) {

                //Now File is exits let's get file content
                $getFileContent = File::get($request->get('file'));

                //We need to convert this file to array of lines
                $arrayOfLines = explode("\n", $getFileContent);

                //Now Let's convert this array to collection to get pagiantion feature and speed up load
                $linesCollection = collect($arrayOfLines)->forPage($request->get('page'), 10);

                //Get Last Page
                $lastPage = count($arrayOfLines) / 10;

                //Now The Data is ready let's return it to response
                return response()->json([
                    "success" => true,
                    "message" => "Log File Has Been Loaded",
                    "body" => [
                        "lines" => $linesCollection,
                        "page" => (int)$request->get('page'),
                        "last" => (int)$lastPage
                    ]
                ], 200);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "Sorry File Not Found",
                    "body" => []
                ], 404);
            }
        }
    }
}
