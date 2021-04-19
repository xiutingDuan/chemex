<?php

namespace App\Http\Controllers;

use App\Models\DeviceRecord;
use App\Models\PartRecord;
use App\Models\SoftwareRecord;
use Celaraze\Response;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\ArrayShape;

class QueryController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * 查询资产.
     *
     * @param $asset_number
     * @return JsonResponse|array
     */
    #[ArrayShape(['code' => "int", 'message' => "string", "data" => "mixed"])]
    public function handle($asset_number): JsonResponse|array
    {
        $asset = DeviceRecord::where('asset_number', $asset_number)->first();
        $asset->user = $asset->admin_user()->value('name');
        $asset->department = $asset->admin_user()->first()?->department()->value('name');
        $asset->category = $asset->category()->value('name');
        $asset->vendor = $asset->vendor()->value('name');
        if (!empty($asset)) {
            return Response::make(200, '查询成功', [$asset]);
        }

        $asset = PartRecord::where('asset_number', $asset_number)->first();
        if (!empty($asset)) {
            $asset->device = $asset->device()->value('name');
            $asset->category = $asset->category()->value('name');
            $asset->vendor = $asset->vendor()->value('name');
            return Response::make(200, '查询成功', [$asset]);
        }

        $asset = SoftwareRecord::where('asset_number', $asset_number)->first();
        if (!empty($asset)) {
            $asset->device = $asset->device()->value('name');
            $asset->category = $asset->category()->value('name');
            $asset->vendor = $asset->vendor()->value('name');
            return Response::make(200, '查询成功', [$asset]);
        }

        return Response::make(404, '没有查询到对应资产');
    }
}
