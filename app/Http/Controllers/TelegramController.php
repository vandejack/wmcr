<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telegram;


date_default_timezone_set("Asia/Makassar");
 
class TelegramController extends Controller
{
    public static function testz(){
        $responseText = "Welcome to the bot! How can I assist you today?";
        Telegram::sendMessage("100356956", $responseText);
    }
}