<?php
namespace Trero\Awning\Controllers;

use Illuminate\Http\Request;
use Trero\Awning\Awning;

class ListFilesController
{
    public function __invoke(Awning $awning) {
        $awning->checkSum();

        $quote = "ciao";
        //return $quote;
        return view('awning::index', compact('quote'));
    }
}