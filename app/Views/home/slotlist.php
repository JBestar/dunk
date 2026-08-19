<!DOCTYPE html>
<html lang="ko" style="">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title></title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

        <!-- 부트스트랩 스타일 -->

        <link rel="stylesheet" href="/css/jquery-ui.css" />

        <!-- 부트스트랩 스크립트 -->
        <!-- 제이쿼리 -->
        <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>

        <script type="text/javascript" src="/js/jquery-ui.js?v=1"></script>

        <script type="text/javascript" src="/js/jquery-form/jquery.form.js"></script>
        <script type="text/javascript" src="/js/jquery-form/jquery.validate.js"></script>

        <style type="text/css"></style>
        <script></script>
    </head>

    <body style="background-color:#141313;">
        <link rel="stylesheet" type="text/css" href="/js/semantic-ui/semantic.css" />
        <!--ui.table-->
        <script src="/js/semantic-ui/semantic.js"></script>
        <script src="/js/vue.js"></script>
        <style>
                      
            @media screen and (min-width:680px) { 
                ::-webkit-scrollbar {width:10px; height:3px; }
                ::-webkit-scrollbar-track {background:#1e1e1e; border-radius:2px 2px 0 0; }
                ::-webkit-scrollbar-thumb {background:#383838; border-radius:2px; }
                ::-webkit-scrollbar-thumb:hover {background:#383838; }
            }

            #gamelist {
                margin: 5px;
                background-color: white;
                padding: 10px;
            }

            .image:hover > img {
                transition-duration: 0.2s;
                transition-timing-function: ease-in;
                transform: scale(1.4);
            }

            .ui.card:hover {
                border: 3px solid gold;
            }

            .image {
                overflow: hidden;
            }
            .image img:hover + .button.start {
                display: block;
            }

            .ui.button.start {
                position: absolute;

                width: 100px;
                height: 40px;

                top: 50%;
                left: 50%;
                margin-top: -20px;
                margin-left: -50px;

                display: none;
            }

            .ui.button.start:hover {
                display: block;
            }

            #gamelist {
                margin: 0px;
                background-color: #141313;
            }
            .ui.cards > .card,
            .ui.card {
                background: #0a182f;
                margin-top: 3px;
                margin-bottom: 3px;
            }
            .ui.cards > .card > .content > .header:not(.ui),
            .ui.card > .content > .header:not(.ui) {
                color: lightgray;
            }
            .ui.grey.cards > .card,
            .ui.cards > .grey.card,
            .ui.grey.card {
                box-shadow: none;
            }
        </style>
        <div id="gamelist">
            <div class="ui inverted segment sticky" style="left: 10px;">
                <!-- <button class="ui button"><i class="ui icon arrow down"></i>인기순</button>  -->
                <button class="ui button" v-on:click="sortABC"><i class="ui icon arrow" v-bind:class="{up:!sort, down:sort}"></i><?=lang('common.order_alphabet')?></button>
                <button class="ui button" v-on:click="sortKo"><i class="ui icon arrow" v-bind:class="{up:!sort2, down:sort2}"></i><?=lang('common.order_korean')?></button>
                <div class="ui action input" v-on:click="search"><input type="text" v-model="keyword" id="search" placeholder="Search..." /> <button class="ui button"><?=lang('common.search')?></button></div>
                <button class="ui icon button" v-on:click="sortBack"><i class="ui undo icon"></i></button>
            </div>
            <div class="ui nine doubling cards" >

                <div class="ui grey card" v-for="item in games">
                    <a class="image item">
                        <img v-bind:src="item.img_1" onerror="this.src='https://semantic-ui.com/images/wireframe/image.png'" /> 
                        <button class="ui teal button start" v-on:click="openGame(item.code)"><?=lang('common.play')?></button>
                    </a>
                    <div class="content"><div class="header">{{item.name_kor}}</div></div>
                </div>
                
            </div>
        </div>
        <script>
            const FURL = "<?=$_ENV['app.furl']?>";
            $(document).ready(function () {
                $(".ui.sticky").sticky({
                    context: "#gamelist",
                });

                $(window).on("unload",function(){
                    if(popWindow != null){
                        if (popWindow.closed == false) {
                            popWindow.close();
                        }
                    }
                });

                parent.document.querySelector("#SLB_film").addEventListener("wheel", preventScroll, { passive: false });
            });

            function preventScroll(e) {
                e.preventDefault();
                e.stopPropagation();

                return false;
            }

            var platform = "PC";
            var popWindow = null;
            var objGameList = new Vue({
                el: "#gamelist",
                data: {
                    cid: <?=$prd?>,
                    ip: "<?=$ip?>",
                    gamesOriginal: [],
                    games: [],
                    sort: false,
                    sort2: false,
                    sort3: false,
                    keyword: "",
                },
                methods: {
                    getGameList: function () {
                        this.games = [
                            <?php foreach($games as $item) : ?>
                            {
                                code: "<?=$item->uuid?>",
                                name_eng: "<?=$item->name?>",
                                name_kor: "<?=$item->name_ko?>",
                                img_1: "<?=$item->img?>",
                                num: 0,
                            },
                            <?php endforeach; ?>
                            
                        ];

                        this.gamesOriginal = [
                            <?php foreach($games as $item) : ?>
                            {
                                code: "<?=$item->uuid?>",
                                name_eng: "<?=$item->name?>",
                                name_kor: "<?=$item->name_ko?>",
                                img_1: "<?=$item->img?>",
                                num: 0,
                            },
                            <?php endforeach; ?>
                            
                        ];
                    },
                    openGame: function (code) {
                        // console.log(FURL + "/slot/xslot?prd=" + this.cid + "&game=" + code);
                        popWindow = window.open(FURL + "/slot/xslot?prd=" + this.cid + "&game=" + code + "&ip=" + this.ip, "games", "width=1200, height=800, left=100, top=50");
                    },
                    search: function () {
                        if (this.keyword == "") {
                            this.sortBack();
                            return;
                        }
                        this.games = this.gamesOriginal.filter((game) => {
                            return game.name_eng.toLowerCase().includes(this.keyword.toLowerCase()) || game.name_kor.toLowerCase().includes(this.keyword.toLowerCase());
                        });
                    },
                    sortABC() {
                        this.sort = !this.sort;
                        if (this.sort == true) {
                            this.games.sort(function (a, b) {
                                return a.name_eng.localeCompare(b.name_eng);
                            });
                        } else {
                            this.games.sort(function (a, b) {
                                return b.name_eng.localeCompare(a.name_eng);
                            });
                        }
                    },
                    sortKo(event) {
                        this.sort2 = !this.sort2;
                        if (this.sort2 == true) {
                            this.games.sort(function (a, b) {
                                return a.name_kor.localeCompare(b.name_kor);
                            });
                        } else {
                            this.games.sort(function (a, b) {
                                return b.name_kor.localeCompare(a.name_kor);
                            });
                        }
                    },
                    sortNum() {
                        this.sort3 = !this.sort3;
                        if (this.sort3 == true) {
                            this.games.sort(function (a, b) {
                                return a.num - b.num;
                            });
                        } else {
                            this.games.sort(function (a, b) {
                                return b.num - a.num;
                            });
                        }
                    },
                    sortBack() {
                        this.keyword = "";
                        this.games = [...this.gamesOriginal];
                    },
                },
                mounted: function () {
                    this.getGameList();
                    // this.sortNum();
                },
                filters: {},
            });
        </script>
    </body>
</html>
