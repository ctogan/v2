<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Code;
use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EntertainmentController extends ApiController
{
    /**
     * @OA\Get(
     *   path="/api/entertainment",
     *   summary="list entertainment",
     *   tags={"entertainment"},
     *     @OA\Parameter(
     *          name="mmses",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *   @OA\Response(
     *     response=200,
     *     description="A list entertainment"
     *   )
     * )
     */
    public function index(Request $request){
        $user = $this->user;
        $games = [
            [
                'title'=>'Friend Math',
                'sub_title' => 'Seberapa lama kamu bertahan?',
                'image'=>'https://scdn.ctree.id/f/200625/1593073004462_Friendmatch_1000x1000.webp',
                'deeplink' => 'cashtree://open.in.web.landscape?url=https://api.ctree.id/static/monstersmatch/index.html?uid='.Utils::encrypt($user->uid).'==&amp;adcl=app8218dcffe093453289&amp;zone='.Code::ADCOLONY_ZONE_ID['skyblaster'].'&amp;adsz=BANNER&amp;inzone=vzbda01495d3ef4ea4b9'
            ],
            [
                'title'=>'Game OX',
                'sub_title' => 'Mainkan game ini, coba keberuntunganmu.',
                'image'=>'https://scdn.ctree.id/f/200710/1594379868866_tebakox.webp',
                'deeplink' => 'cashtree://web/game/ab/v2?inst_type=297'
            ]
        ];
        $leaderboard = [
            [
                'image' => 'https://scdn.ctree.id/f/200625/1593073004462_Friendmatch_1000x1000.webp',
                'deeplink' => 'cashtree://webnew/cashplus/leaderboard/game?title=Leaderboard&gm=2',
                'rank' => [
                    [
                        'name' => 'Rifa',
                        'point' => '12000P'
                    ],
                    [
                        'name' => 'Kervin',
                        'point' => '10000P'
                    ],
                    [
                        'name' => 'Ogan',
                        'point' => '9000P'
                    ],
                    [
                        'name' => 'Ridcat',
                        'point' => '8000P'
                    ]
                ]
            ]
        ];
        $comic = [
            [
                'image' => 'https://scdn.ctree.id/f/200901/1598928562071_Banner_front.webp',
                'deeplink' => 'cashtree://webnew/cashplus/comiccenter?p=19&adcl=app8218dcffe093453289&zone=vz5ab55c75fe154bcbb2&adsz=BANNER&interstitial=on&placement='.Code::INMOBY_PLACEMENT_ID['19'],
            ]
        ];
        $horoscope = [
            [
                'image'=>'https://scdn.ctree.id/f/200724/1595557820124_Banner_front_leaderboard.webp',
                'deeplink' => 'cashtree://webnew/cashplus/leaderboard/game?title=Leaderboard&amp;gm=2'
            ]
        ];
        $response = [
            'entertainment' => [
                [
                    'content_type' => 'games',
                    'title' => 'Games',
                    'sub_title' => 'Mainkan dan menangkan hadianya',
                    'data' => $games
                ],
                [
                    'content_type' => 'leader_board',
                    'title' => 'Leader Board',
                    'sub_title' => 'Mainkan dan menangkan hadianya',
                    'data' => $leaderboard
                ],
                [
                    'content_type' => 'comic',
                    'title' => 'Komik',
                    'sub_title' => 'Baca Komik terbaru disini',
                    'data' => $comic
                ],
                [
                    'content_type' => 'horoscope',
                    'title' => 'Horoscope',
                    'sub_title' => 'Mau tau zodiak kamu hari ini? Cek disini.',
                    'data' => $horoscope
                ]
            ],
        ];

        return $this->successResponse($response);
    }
}
