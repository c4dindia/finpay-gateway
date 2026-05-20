<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    public function downloadP3H2HDoc()
    {
        $filepath = public_path('docs/P3_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P3 Host-2-Host Documentation.docx');
    }

    public function downloadP3PayByLinkDoc()
    {
        $filepath = public_path('docs/API_Doc_P3_PayByLink_RyzenPay.docx');
        return response()->download($filepath, 'Pay-By-Link(X)_Doc.docx');
    }

    public function downloadP4H2HDoc()
    {
        $filepath = public_path('docs/P4_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P4 Host-2-Host Documentation.docx');
    }

    public function downloadP7H2HDoc()
    {
        $filepath = public_path('docs/P7_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P7 Host-2-Host Documentation.docx');
    }

    public function downloadP8H2HDoc()
    {
        $filepath = public_path('docs/P8_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P8 Host-2-Host Documentation.docx');
    }
    public function downloadP11H2HDoc()
    {
        $filepath = public_path('docs/P11_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P11 Host-2-Host Documentation.docx');
    }

    public function downloadP12H2HDoc()
    {
        $filepath = public_path('docs/P12_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P12 Host-2-Host Documentation.docx');
    }

    public function downloadP13H2HDoc()
    {
        $filepath = public_path('docs/P13_HOST2HOST_API_DOC.docx');
        return response()->download($filepath, 'P13 Host-2-Host Documentation.docx');
    }
}
