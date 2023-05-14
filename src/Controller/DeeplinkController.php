<?php

namespace App\Controller;


use App\Controller\V1\BaseController;
use Phos\Controller\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeeplinkController extends BaseController
{
    public function assetsLink()
    {

        $response = '[
      {
        "relation": [
          "delegate_permission/common.handle_all_urls"
        ],
        "target": {
          "namespace": "android_app",
          "package_name": "digital.paynetics.phos",
          "sha256_cert_fingerprints": [
            "FF:63:93:13:32:F8:65:1E:26:D2:DF:77:E2:71:CE:73:5C:9B:56:C3:A4:0B:E9:57:08:B6:51:11:0F:91:68:65",
            "F7:2F:06:AE:D0:BB:F3:C5:2A:10:25:34:97:C9:7C:F0:23:9E:EE:50:77:70:A6:F4:31:ED:2E:A1:26:2C:42:DF",
            "26:B2:23:9F:AF:6D:18:2B:F3:FD:85:DE:AD:1A:8F:25:88:F4:94:BC:0A:9F:5E:18:2A:74:BB:44:A0:05:06:0F"
          ]
        }
      },
      {
        "relation": [
          "delegate_permission/common.handle_all_urls"
        ],
        "target": {
          "namespace": "android_app",
          "package_name": "com.curb.android.curbpay",
          "sha256_cert_fingerprints": [
            "91:73:F0:C5:6B:A0:83:C1:76:D8:D1:94:51:23:C0:30:03:1A:60:74:7F:60:EC:9D:2C:3A:9F:0E:CD:F3:D2:B6",
            "8B:EB:A9:90:4A:25:21:EA:71:E4:AC:96:56:49:F9:C3:A6:17:46:B7:5C:77:16:C8:42:73:9F:47:BF:02:24:1C"
          ]
        }
      },
       {
        "relation": [
          "delegate_permission/common.handle_all_urls"
        ],
        "target": {
          "namespace": "android_app",
          "package_name": "cy.com.jcc.softpos",
          "sha256_cert_fingerprints": [
            "79:57:4E:1B:AE:27:4A:E5:DE:FB:88:42:42:0E:C5:AC:BB:19:12:58:72:6A:B0:E8:B1:6D:1A:3C:16:8E:E3:9E",
          ]
        }
      },
      {
        "relation": [
          "delegate_permission/common.handle_all_urls"
        ],
        "target": {
          "namespace": "android_app",
          "package_name": "cloud.phos.cmcom",
          "sha256_cert_fingerprints": [
            "F7:2F:06:AE:D0:BB:F3:C5:2A:10:25:34:97:C9:7C:F0:23:9E:EE:50:77:70:A6:F4:31:ED:2E:A1:26:2C:42:DF",
          ]
        }
      },
      {
        "relation": [
          "delegate_permission/common.handle_all_urls"
        ],
        "target": {
          "namespace": "android_app",
          "package_name": "maxaa.pos",
          "sha256_cert_fingerprints": [
            "C5:BB:57:C2:E6:94:87:68:5D:A4:CA:60:DD:F0:F3:E0:75:2C:98:23:7E:64:97:67:5A:74:5A:DD:34:43:BF:35",
          ]
        }
      },
      {
        "relation": [
          "delegate_permission/common.handle_all_urls"
        ],
        "target": {
          "namespace": "android_app",
          "package_name": "tech.wellet.app",
          "sha256_cert_fingerprints": [
            "31:B2:09:64:BB:DB:8C:85:8E:51:19:B5:2F:42:53:BE:7B:06:01:F6:39:DB:31:D2:F5:39:74:3E:B8:F3:0C:86",
          ]
        }
      }
    ]';

        return new Response($response, 200, array("Content-type" => 'application/json'));

    }
}