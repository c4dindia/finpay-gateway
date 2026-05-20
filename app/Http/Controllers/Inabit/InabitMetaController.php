<?php

namespace App\Http\Controllers\Inabit;

use App\Http\Controllers\Controller;
use App\Services\InabitMetadataService;
use Illuminate\Http\Request;

class InabitMetaController extends Controller
{
    public function __construct(
        protected InabitMetadataService $meta
    ) {}

    public function financialAsset(Request $request)
    {
        $code = $request->query('code', 'USDT'); // default for testing
        $asset = $meta = $this->meta->getFinancialAssetByCode($code);

        if (!$asset) {
            return response()->json([
                'message' => "Financial asset with code {$code} not found",
            ], 404);
        }

        return response()->json($asset);
    }

    public function blockchainByCode(Request $request)
    {
        $code = $request->query('code', 'ethereum'); // default for testing
        $chain = $this->meta->getBlockchainByCode($code);

        if (!$chain) {
            return response()->json([
                'message' => "Blockchain with code {$code} not found",
            ], 404);
        }

        return response()->json($chain);
    }

    public function blockchainByName(Request $request)
    {
        $name = $request->query('name', 'Bitcoin'); // default
        $chain = $this->meta->getBlockchainByName($name);

        if (!$chain) {
            return response()->json([
                'message' => "Blockchain with name {$name} not found",
            ], 404);
        }

        return response()->json($chain);
    }
}
