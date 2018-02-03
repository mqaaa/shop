function arraycopy(e, a, t, o, n) {
    for (i = 0; i < n; i++) t[o + i] = e[a + i]
}
function arrayEquals(e, a) {
    if ("undefined" == e || "undefined" == a) return ! 1;
    if (e instanceof Array && a instanceof Array) {
        if (e.length != a.length) return ! 1;
        for (i = 0; i < e.length; i++) if (e[i] != a[i]) return ! 1;
        return ! 0
    }
    return ! 1
}
function isImage(e) {
    return null !== getImageMime(e)
}
function getImageMime(e) {
    if (null == e || "undefined" == e || e.length < 8) return null;
    var a = [];
    return arraycopy(e, 0, a, 0, 6),
    isGif(a) ? "image/gif": (a = [], arraycopy(e, 6, a, 0, 4), isJpeg(a) ? "image/jpeg": (a = [], arraycopy(e, 0, a, 0, 8), isPng(a) ? "image/png": null))
}
function isAudio(e) {
    if (null == e || "undefined" == e || e.length < 12) return null;
    var a = [];
    arraycopy(e, 0, a, 0, 4);
    var t = [];
    return arraycopy(e, 8, t, 0, 4),
    isWav(a, t) ? "audio/wav": null
}
function isGif(e) {
    return arrayEquals(e, gifMagic0) || arrayEquals(e, getGifMagic1)
}
function isJpeg(e) {
    return arrayEquals(e, jpegMagic) || arrayEquals(e, jpeg_jfif) || arrayEquals(e, jpeg_exif)
}
function isPng(e) {
    return arrayEquals(e, pngMagic)
}
function isWav(e, a) {
    return arrayEquals(e, wavMagic1) && arrayEquals(a, wavMagic2)
}
function getUUID() {
    var e = (new Date).getTime(),
    a = "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g,
    function(a) {
        var t = (e + 16 * Math.random()) % 16 | 0;
        return e = Math.floor(e / 16),
        ("x" == a ? t: 3 & t | 8).toString(16)
    });
    return a = a.replace(new RegExp("-", "g"), "")
}
var Util = {
    prevKey: void 0,
    closeAlert: function() {
        var e = $("#alertDialogPanel");
        e.prev().remove(),
        e.remove()
    },
    alert: function(e) {
        var a = "",
        t = '<div onclick="Util.closeAlert(this)" style="height: ' + document.documentElement.scrollHeight + 'px;display: block;" class="dialog-background"></div>',
        o = '<div class="dialog-panel" id="alertDialogPanel" tabindex="0" onkeyup="Util.closeAlert()"><div class="fn-clear dialog-header-bg"><a href="javascript:void(0);" onclick="Util.closeAlert()" class="icon-close"></a></div><div class="dialog-main" style="text-align:center;padding: 30px 10px 40px">' + e + "</div></div>";
        a = t + o,
        $("body").append(a),
        $("#alertDialogPanel").css({
            top: ($(window).height() - $("#alertDialogPanel").height()) / 2 + "px",
            left: ($(window).width() - $("#alertDialogPanel").width()) / 2 + "px",
            outline: "none"
        }).show().focus()
    },
    makeNotificationRead: function(e) {
        $.ajax({
            url: Label.servePath + "/notification/read/" + e,
            type: "GET",
            cache: !1,
            success: function(e, a) {
                e.sc && window.location.reload()
            }
        })
    },
    _initCommonHotKey: function() {
        if (!Label.userKeyboardShortcutsStatus || "1" === Label.userKeyboardShortcutsStatus) return ! 1;
        var e = function(e) {
            var a = $(".list > ul > li.focus");
            if (1 === a.length) {
                if ("top" === e || "bottom" === e) return $(window).scrollTop(a.offset().top),
                !1; ($(window).height() + $(window).scrollTop() < a.offset().top + a.outerHeight() || $(window).scrollTop() > a.offset().top) && ("down" === e ? $(window).scrollTop(a.offset().top - ($(window).height() - a.outerHeight())) : $(window).scrollTop(a.offset().top))
            }
        };
        0 === $("#articleTitle").length && $(document).bind("keydown", "c",
        function(e) {
            return ! Util.prevKey && (window.location = Label.servePath + "/post?type=0", !1)
        }),
        $(document).bind("keyup", "g",
        function() {
            return Util.prevKey = "g",
            setTimeout(function() {
                Util.prevKey = void 0
            },
            1e3),
            !1
        }).bind("keyup", "s",
        function() {
            return $("#search").focus(),
            !1
        }).bind("keyup", "t",
        function() {
            return void 0 === Util.prevKey && Util.goTop(),
            !1
        }).bind("keyup", "n",
        function(e) {
            return "g" === Util.prevKey && (window.location = Label.servePath + "/notifications"),
            !1
        }).bind("keyup", "h",
        function(e) {
            return "g" === Util.prevKey && (window.location = Label.servePath + "/hot"),
            !1
        }).bind("keyup", "i",
        function(e) {
            return "g" === Util.prevKey && (window.location = Label.servePath),
            !1
        }).bind("keyup", "r",
        function(e) {
            return "g" === Util.prevKey && (window.location = Label.servePath + "/recent"),
            !1
        }).bind("keyup", "p",
        function(e) {
            return "g" === Util.prevKey && (window.location = Label.servePath + "/perfect"),
            !1
        }).bind("keyup", "t",
        function(e) {
            return "g" === Util.prevKey && (window.location = Label.servePath + "/timeline"),
            !1
        }).bind("keyup", "Shift+/",
        function(e) {
            return window.open("https://hacpai.com/article/1474030007391"),
            !1
        }).bind("keyup", "j",
        function(a) {
            var t = ".content .list:last > ul > ";
            1 === $("#comments").length && (t = "#comments .list > ul > ");
            var o = $(t + "li.focus");
            return 0 === o.length ? $(t + "li:first").addClass("focus") : 1 === o.next().length && (o.next().addClass("focus"), o.removeClass("focus")),
            e("down"),
            !1
        }).bind("keyup", "k",
        function(a) {
            var t = ".content .list:last > ul > ";
            1 === $("#comments").length && (t = "#comments .list > ul > ");
            var o = $(t + "li.focus");
            return 0 === o.length ? $(t + "li:last").addClass("focus") : 1 === o.prev().length && (o.prev().addClass("focus"), o.removeClass("focus")),
            e("up"),
            !1
        }).bind("keyup", "f",
        function(a) {
            var t = ".content .list:last > ul > ";
            return 1 === $("#comments").length && (t = "#comments .list > ul > "),
            $(t + "li.focus").removeClass("focus"),
            $(t + "li:first").addClass("focus"),
            e("top"),
            !1
        }).bind("keyup", "l",
        function(a) {
            if (Util.prevKey) return ! 1;
            var t = ".content .list:last > ul > ";
            return 1 === $("#comments").length && (t = "#comments .list > ul > "),
            $(t + "li.focus").removeClass("focus"),
            $(t + "li:last").addClass("focus"),
            e("bottom"),
            !1
        }).bind("keyup", "o",
        function(e) {
            if (1 === $("#comments").length) return ! 1;
            var a = $(".content .list:last > ul > li.focus > h2 > a").attr("href");
            return a || (a = $(".content .list:last > ul > li.focus .fn-flex-1 > h2 > a").attr("href")),
            a || (a = $(".content .list:last > ul > li.focus h2.fn-flex-1 > a").attr("href")),
            a && (window.location = a),
            !1
        }).bind("keyup", "return",
        function(e) {
            if (1 === $("#comments").length) return ! 1;
            var a = $(".content .list:last > ul > li.focus > h2 > a").attr("href");
            return a || (a = $(".content .list:last > ul > li.focus .fn-flex-1 > h2 > a").attr("href")),
            a || (a = $(".content .list:last > ul > li.focus h2.fn-flex-1 > a").attr("href")),
            a && (window.location = a),
            !1
        })
    },
    notifyMsg: function(e) {
        if (! ("Notification" in window)) return ! 1;
        var a = function(e) {
            var a = new Notification(Label.visionLabel, {
                body: Label.desktopNotificationTemplateLabel.replace("${count}", e),
                icon: Label.staticServePath + "/images/faviconH.png"
            });
            a.onclick = a.onerror = function() {
                window.location = Label.servePath + "/notifications"
            }
        };
        "granted" === Notification.permission ? a(e) : "denied" !== Notification.permission && Notification.requestPermission(function(t) {
            "granted" === t && a(e)
        })
    },
    linkForge: function() {
        $(".link-forge .module-header > a").click(function() {
            var e = $(this).closest(".module").find(".module-panel");
            return "hidden" !== e.css("overflow") ? (e.css({
                "max-height": "409px",
                overflow: "hidden"
            }), !1) : void e.css({
                "max-height": "inherit",
                overflow: "inherit"
            })
        });
        var e = function() {
            return Label.isLoggedIn ? void(Validate.goValidate({
                target: $("#uploadLinkTip"),
                data: [{
                    target: $(".link-forge-upload input"),
                    type: "url",
                    msg: Label.invalidUserURLLabel
                }]
            }) && $.ajax({
                url: Label.servePath + "/forge/link",
                type: "POST",
                cache: !1,
                data: JSON.stringify({
                    url: $(".link-forge-upload input").val()
                }),
                error: function(e, a, t) {
                    alert(t)
                },
                success: function(e, a) {
                    e.sc ? ($("#uploadLinkTip").html("<ul><li>" + Label.forgeUploadSuccLabel + "</li></ul>").addClass("succ"), $(".link-forge-upload input").val(""), setTimeout(function() {
                        $("#uploadLinkTip").html("").removeClass("succ")
                    },
                    5e3)) : alert(e.msg)
                }
            })) : (Util.needLogin(), !1)
        };
        $(".link-forge-upload button").click(function() {
            e()
        }),
        $(".link-forge-upload input").focus().keypress(function(a) {
            return 13 === a.which ? (e(), !1) : void Validate.goValidate({
                target: $("#uploadLinkTip"),
                data: [{
                    target: $(".link-forge-upload input"),
                    type: "url",
                    msg: Label.invalidUserURLLabel
                }]
            })
        })
    },
    processClipBoard: function(e, a) {
        if ("" === e.getData("text/html") && 2 === e.items.length) return "";
        var t = toMarkdown(e.getData("text/html"), {
            converters: [{
                filter: "img",
                replacement: function(e, t) {
                    if (1 === t.attributes.length) return "";
                    var o = {
                        url: t.src
                    };
                    return $.ajax({
                        url: Label.servePath + "/fetch-upload",
                        type: "POST",
                        data: JSON.stringify(o),
                        cache: !1,
                        success: function(e, t) {
                            if (e.sc) {
                                var o = a.getValue();
                                o = o.replace(e.originalURL, e.url),
                                a.setValue(o)
                            }
                        }
                    }),
                    "![](" + t.src + ")"
                }
            }],
            gfm: !0
        });
        return t = $("<div>" + t + "</div>").text().replace(/\n{2,}/g, "\n\n").replace(/ /g, " "),
        $.trim(t)
    },
    getParameterByName: function(e) {
        e = e.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var a = new RegExp("[\\?&]" + e + "=([^&#]*)"),
        t = a.exec(location.search);
        return null === t ? "": decodeURIComponent(t[1].replace(/\+/g, " "))
    },
    getDeviceByUa: function(e) {
        $.ua.set(e);
        var a = $.ua.device.model ? $.ua.device.model: $.ua.os.name;
        return a && "Windows" !== a || (a = ""),
        a
    },
    initSearch: function(e, a, t) {
        var o = algoliasearch(e, a),
        i = o.initIndex(t);
        $("#search").autocomplete({
            hint: !1,
            templates: {
                footer: '<div class="fn-right fn-pointer" onclick="window.open(\'https://www.algolia.com/referrals/1faf0d17/join\')"><span class="ft-gray">With &hearts; from</span> <img src="' + Label.staticServePath + '/images/services/algolia128x40.png" /></div>'
            }
        },
        [{
            source: function(e, a) {
                i.search(e, {
                    hitsPerPage: 20
                },
                function(e, t) {
                    return e ? void a([]) : void a(t.hits, t)
                })
            },
            displayKey: "name",
            templates: {
                suggestion: function(e) {
                    return e._highlightResult.articleTitle.value
                }
            }
        }]).on("autocomplete:selected",
        function(e, a, t) {
            window.open("/article/" + a.oId)
        }).bind("keyup", "esc",
        function() {
            $(this).blur()
        })
    },
    initTextarea: function(e, a) {
        var t = {
            $it: $("#" + e),
            setValue: function(e) {
                this.$it.val(e)
            },
            getValue: function() {
                return this.$it.val()
            },
            setOption: function(e, a) {
                this.$it.prop(e, a)
            },
            focus: function() {
                this.$it.focus()
            },
            setCursor: function() {
                this.$it[0].setSelectionRange(0, 0)
            }
        };
        return a && "function" == typeof a && t.$it.keyup(function() {
            a(t)
        }),
        t
    },
    initCodeMirror: function() {
        var e = "+1,-1,100,1234,8ball,a,ab,abc,abcd,accept,aerial_tramway,airplane,alarm_clock,alien,ambulance,anchor,angel,anger,angry,anguished,ant,apple,aquarius,aries,arrows_clockwise,arrows_counterclockwise,arrow_backward,arrow_double_down,arrow_double_up,arrow_down,arrow_down_small,arrow_forward,arrow_heading_down,arrow_heading_up,arrow_left,arrow_lower_left,arrow_lower_right,arrow_right,arrow_right_hook,arrow_up,arrow_upper_left,arrow_upper_right,arrow_up_down,arrow_up_small,art,articulated_lorry,astonished,atm,b,baby,baby_bottle,baby_chick,baby_symbol,back,baggage_claim,balloon,ballot_box_with_check,bamboo,banana,bangbang,bank,barber,bar_chart,baseball,basketball,bath,bathtub,battery,bear,bee,beer,beers,beetle,beginner,bell,bento,bicyclist,bike,bikini,bird,birthday,black_circle,black_joker,black_large_square,black_medium_small_square,black_medium_square,black_nib,black_small_square,black_square_button,blossom,blowfish,blue_book,blue_car,blue_heart,blush,boar,boat,bomb,book,bookmark,bookmark_tabs,books,boom,boot,bouquet,bow,bowling,boy,bread,bride_with_veil,bridge_at_night,briefcase,broken_heart,bug,bulb,bullettrain_front,bullettrain_side,bus,busstop,busts_in_silhouette,bust_in_silhouette,c,cactus,cake,calendar,calling,camel,camera,cancer,candy,capital_abcd,capricorn,car,card_index,carousel_horse,cat,cat2,cd,chart,chart_with_downwards_trend,chart_with_upwards_trend,checkered_flag,cherries,cherry_blossom,chestnut,chicken,children_crossing,chocolate_bar,christmas_tree,church,cinema,circus_tent,city_sunrise,city_sunset,cl,clap,clapper,clipboard,clock1,clock10,clock1030,clock11,clock1130,clock12,clock1230,clock130,clock2,clock230,clock3,clock330,clock4,clock430,clock5,clock530,clock6,clock630,clock7,clock730,clock8,clock830,clock9,clock930,closed_book,closed_lock_with_key,closed_umbrella,cloud,clubs,cn,cocktail,coffee,cold_sweat,collision,computer,confetti_ball,confounded,confused,congratulations,construction,construction_worker,convenience_store,cookie,cool,cop,copyright,corn,couple,couplekiss,couple_with_heart,cow,cow2,credit_card,crescent_moon,crocodile,crossed_flags,crown,cry,crying_cat_face,crystal_ball,cupid,curly_loop,currency_exchange,curry,custard,customs,cyclone,d,dancer,dancers,dango,dart,dash,date,de,deciduous_tree,department_store,diamonds,diamond_shape_with_a_dot_inside,disappointed,disappointed_relieved,dizzy,dizzy_face,dog,dog2,dollar,dolls,dolphin,door,doughnut,do_not_litter,dragon,dragon_face,dress,dromedary_camel,droplet,dvd,e-mail,e50a,ear,earth_africa,earth_americas,earth_asia,ear_of_rice,egg,eggplant,eight,eight_pointed_black_star,eight_spoked_asterisk,electric_plug,elephant,email,end,envelope,es,euro,european_castle,european_post_office,evergreen_tree,exclamation,expressionless,eyeglasses,eyes,f,facepunch,factory,fallen_leaf,family,fast_forward,fax,fearful,feet,ferris_wheel,file_folder,fire,fireworks,fire_engine,first_quarter_moon,first_quarter_moon_with_face,fish,fishing_pole_and_fish,fish_cake,fist,five,flags,flashlight,floppy_disk,flower_playing_cards,flushed,foggy,football,fork_and_knife,fountain,four,four_leaf_clover,fr,free,fried_shrimp,fries,frog,frowning,fuelpump,full_moon,full_moon_with_face,g,game_die,gb,gem,gemini,ghost,gift,gift_heart,girl,globe_with_meridians,goat,golf,grapes,green_apple,green_book,green_heart,grey_exclamation,grey_question,grimacing,grin,grinning,guardsman,guitar,gun,haircut,hamburger,hammer,hamster,hand,handbag,hankey,hash,hatched_chick,hatching_chick,headphones,heart,heartbeat,heartpulse,hearts,heart_decoration,heart_eyes,heart_eyes_cat,hear_no_evil,heavy_check_mark,heavy_division_sign,heavy_dollar_sign,heavy_exclamation_mark,heavy_minus_sign,heavy_multiplication_x,heavy_plus_sign,helicopter,herb,hibiscus,high_brightness,high_heel,hocho,honeybee,honey_pot,horse,horse_racing,hospital,hotel,hotsprings,hourglass,hourglass_flowing_sand,house,house_with_garden,hushed,i,icecream,ice_cream,id,ideograph_advantage,imp,inbox_tray,incoming_envelope,information_desk_person,information_source,innocent,interrobang,iphone,it,izakaya_lantern,j,jack_o_lantern,japan,japanese_castle,japanese_goblin,japanese_ogre,jeans,joy,joy_cat,jp,k,key,keycap_ten,kimono,kiss,kissing,kissing_cat,kissing_closed_eyes,kissing_heart,kissing_smiling_eyes,koala,koko,kr,large_blue_circle,large_blue_diamond,large_orange_diamond,last_quarter_moon,last_quarter_moon_with_face,laughing,leaves,ledger,leftwards_arrow_with_hook,left_luggage,left_right_arrow,lemon,leo,leopard,libra,light_rail,link,lips,lipstick,lock,lock_with_ink_pen,lollipop,loop,loudspeaker,love_hotel,love_letter,low_brightness,m,mag,mag_right,mahjong,mailbox,mailbox_closed,mailbox_with_mail,mailbox_with_no_mail,man,mans_shoe,man_with_gua_pi_mao,man_with_turban,maple_leaf,mask,massage,meat_on_bone,mega,melon,memo,mens,metro,microphone,microscope,milky_way,minibus,minidisc,mobile_phone_off,moneybag,money_with_wings,monkey,monkey_face,monorail,mortar_board,mountain_bicyclist,mountain_cableway,mountain_railway,mount_fuji,mouse,mouse2,movie_camera,moyai,muscle,mushroom,musical_keyboard,musical_note,musical_score,mute,nail_care,name_badge,necktie,negative_squared_cross_mark,neutral_face,new,newspaper,new_moon,new_moon_with_face,ng,nine,non-potable_water,nose,notebook,notebook_with_decorative_cover,notes,no_bell,no_bicycles,no_entry,no_entry_sign,no_good,no_mobile_phones,no_mouth,no_pedestrians,no_smoking,nut_and_bolt,o,o2,ocean,octocat,octopus,oden,office,ok,ok_hand,ok_woman,older_man,older_woman,on,oncoming_automobile,oncoming_bus,oncoming_police_car,oncoming_taxi,one,open_file_folder,open_hands,open_mouth,ophiuchus,orange_book,outbox_tray,ox,package,pager,page_facing_up,page_with_curl,palm_tree,panda_face,paperclip,parking,partly_sunny,part_alternation_mark,passport_control,paw_prints,peach,pear,pencil,pencil2,penguin,pensive,performing_arts,persevere,person_frowning,person_with_blond_hair,person_with_pouting_face,phone,pig,pig2,pig_nose,pill,pineapple,pisces,pizza,point_down,point_left,point_right,point_up,point_up_2,police_car,poodle,poop,postal_horn,postbox,potable_water,pouch,poultry_leg,pound,pouting_cat,pray,princess,punch,purple_heart,purse,pushpin,put_litter_in_its_place,question,r,rabbit,rabbit2,racehorse,radio,radio_button,rage,railway_car,rainbow,raised_hand,raised_hands,raising_hand,ram,ramen,rat,recycle,red_car,red_circle,registered,relaxed,relieved,repeat,repeat_one,restroom,revolving_hearts,rewind,ribbon,rice,rice_ball,rice_cracker,rice_scene,ring,rocket,roller_coaster,rooster,rose,rotating_light,round_pushpin,rowboat,ru,rugby_football,running,running_shirt_with_sash,sa,sagittarius,sailboat,sake,sandal,santa,satellite,satisfied,saxophone,school,school_satchel,scissors,scorpius,scream,scream_cat,scroll,seat,secret,seedling,see_no_evil,seven,shaved_ice,sheep,shell,ship,shirt,shoe,shower,signal_strength,six,six_pointed_star,ski,skull,sleeping,sleepy,slot_machine,small_blue_diamond,small_orange_diamond,small_red_triangle,small_red_triangle_down,smile,smiley,smiley_cat,smile_cat,smiling_imp,smirk,smirk_cat,smoking,snail,snake,snowboarder,snowflake,snowman,sob,soccer,soon,sos,sound,space_invader,spades,spaghetti,sparkle,sparkler,sparkles,sparkling_heart,speaker,speak_no_evil,speech_balloon,speedboat,squirrel,star,star2,stars,station,statue_of_liberty,steam_locomotive,stew,straight_ruler,strawberry,stuck_out_tongue,stuck_out_tongue_closed_eyes,stuck_out_tongue_winking_eye,sunflower,sunglasses,sunny,sunrise,sunrise_over_mountains,sun_with_face,surfer,sushi,suspension_railway,sweat,sweat_drops,sweat_smile,sweet_potato,swimmer,symbols,syringe,tada,tanabata_tree,tangerine,taurus,taxi,tea,telephone,telephone_receiver,telescope,tennis,tent,thought_balloon,three,thumbsdown,thumbsup,ticket,tiger,tiger2,tired_face,tm,toilet,tokyo_tower,tomato,tongue,top,tophat,tractor,traffic_light,train,train2,tram,triangular_flag_on_post,triangular_ruler,trident,triumph,trolleybus,trollface,trophy,tropical_drink,tropical_fish,truck,trumpet,tshirt,tulip,turtle,tv,twisted_rightwards_arrows,two,two_hearts,two_men_holding_hands,two_women_holding_hands,u,u5272,u5408,u55b6,u6307,u6708,u6709,u6e80,u7121,u7533,u7981,u7a7a,umbrella,unamused,underage,unicorn_face,unlock,up,us,v,vertical_traffic_light,vhs,vibration_mode,video_camera,video_game,violin,virgo,volcano,vs,walking,waning_crescent_moon,waning_gibbous_moon,warning,watch,watermelon,water_buffalo,wave,wavy_dash,waxing_crescent_moon,waxing_gibbous_moon,wc,weary,wedding,whale,whale2,wheelchair,white_check_mark,white_circle,white_flower,white_large_square,white_medium_small_square,white_medium_square,white_small_square,white_square_button,wind_chime,wine_glass,wink,wolf,woman,womans_clothes,womans_hat,womens,worried,wrench,x,yellow_heart,yen,yum,zap,zero,zzz",
        a = "";
        if ($.ajax({
            async: !1,
            url: Label.servePath + "/users/emotions",
            type: "GET",
            success: function(e) {
                a = e.emotions
            }
        }), "" === a) a = e;
        else {
            for (var t = a.split(","), o = 0; o < t.length; o++) e = e.replace(t[o] + ",", ",");
            a = a + "," + e
        }
        a = a.replace(/,+/g, ",");
        for (var i = a.split(/,/), n = [], r = 0; r < i.length; r++) {
            var l = i[r],
            s = i[r];
            n.push({
                displayText: "<span>" + l + '&nbsp;<img style="width: 16px" src="' + Label.staticServePath + "/emoji/graphics/" + s + '.png"></span>',
                text: s + ": "
            })
        }
        Label.commonAtUser && "true" === Label.commonAtUser && CodeMirror.registerHelper("hint", "userName",
        function(e) {
            for (var a = /[\w$]+/,
            t = e.getCursor(), o = e.getLine(t.line), i = t.ch, n = i; n < o.length && a.test(o.charAt(n));)++n;
            for (; i && a.test(o.charAt(i - 1));)--i;
            var r = e.getTokenAt(t),
            l = [];
            return 0 === r.string.indexOf("@") && ($.ajax({
                async: !1,
                url: Label.servePath + "/users/names?name=" + r.string.substring(1),
                type: "GET",
                success: function(a) {
                    if (a.sc && a.userNames) {
                        for (var t = 0; t < a.userNames.length; t++) {
                            var o = a.userNames[t],
                            i = o.userName,
                            n = o.userAvatarURL;
                            l.push({
                                displayText: "<span style='font-size: 1rem;line-height:22px'><img style='width: 1rem;height: 1rem;margin:3px 0;float:left' src='" + n + "'> " + i + "</span>",
                                text: i + " "
                            })
                        }
                        "comment" === e.
                        for && l.push({
                            displayText: "<span style='font-size: 1rem;line-height:22px'><img style='width: 1rem;height: 1rem;margin:3px 0;float:left' src='/images/user-thumbnail.png'> @参与者</span>",
                            text: "participants "
                        })
                    }
                }
            }), {
                list: l,
                from: CodeMirror.Pos(t.line, i),
                to: CodeMirror.Pos(t.line, n)
            })
        }),
        CodeMirror.registerHelper("hint", "emoji",
        function(e) {
            for (var a = /[\w$]+/,
            t = e.getCursor(), o = e.getLine(t.line), n = t.ch, r = n; r < o.length && a.test(o.charAt(r));)++r;
            for (; n && a.test(o.charAt(n - 1));)--n;
            for (var l = e.getTokenAt(t), s = [], c = l.string.trim(), u = 0, d = 0; d < i.length; d++) {
                var f = i[d],
                g = i[d];
                if (Util.startsWith(g, c) && (s.push({
                    displayText: '<span style="font-size: 1rem;line-height:22px"><img style="width: 1rem;margin:3px 0;float:left" src="' + Label.staticServePath + "/emoji/graphics/" + g + '.png"> ' + f.toString() + "</span>",
                    text: ":" + g + ": "
                }), u++), u > 10) break
            }
            return {
                list: s,
                from: CodeMirror.Pos(t.line, n),
                to: CodeMirror.Pos(t.line, r)
            }
        }),
        CodeMirror.commands.autocompleteUserName = function(e) {
            return e.showHint({
                hint: CodeMirror.hint.userName,
                completeSingle: !1
            }),
            CodeMirror.Pass
        },
        CodeMirror.commands.autocompleteEmoji = function(e) {
            return e.showHint({
                hint: CodeMirror.hint.emoji,
                completeSingle: !1
            }),
            CodeMirror.Pass
        },
        CodeMirror.commands.startAudioRecord = function(e) {
            if (Audio.availabel || Audio.init(function() {
                var a = e.getCursor();
                e.replaceRange(Label.audioRecordingLabel, a),
                Audio.handleStartRecording()
            }), Audio.availabel) {
                var a = e.getCursor();
                e.replaceRange(Label.audioRecordingLabel, a),
                Audio.handleStartRecording()
            }
        },
        CodeMirror.commands.endAudioRecord = function(e) {
            if (Audio.availabel) {
                Audio.handleStopRecording();
                var a = e.getCursor();
                e.replaceRange(Label.uploadingLabel, CodeMirror.Pos(a.line, a.ch - Label.audioRecordingLabel.length), a);
                var t = Audio.wavFileBlob.getDataBlob(),
                o = Math.floor(100 * Math.random()) + "" + (new Date).getTime() + Math.floor(100 * Math.random()) + ".wav",
                i = new FileReader;
                i.onload = function(a) {
                    if ("" !== Label.qiniuUploadToken) {
                        var i = new FormData;
                        i.append("token", Label.qiniuUploadToken),
                        i.append("file", t),
                        i.append("key", o),
                        $.ajax({
                            type: "POST",
                            url: "https://up.qbox.me/",
                            data: i,
                            processData: !1,
                            contentType: !1,
                            paramName: "file",
                            success: function(a) {
                                var t = e.getCursor();
                                e.replaceRange('<audio controls="controls" src="' + Label.qiniuDomain + "/" + o + '"></audio>\n\n', CodeMirror.Pos(t.line, t.ch - Label.uploadingLabel.length), t)
                            },
                            error: function(a, t, o) {
                                alert("Error: " + o);
                                var i = e.getCursor();
                                e.replaceRange("", CodeMirror.Pos(i.line, i.ch - Label.uploadingLabel.length), i)
                            }
                        })
                    } else {
                        var i = new FormData;
                        i.append("file", t),
                        i.append("key", o),
                        $.ajax({
                            type: "POST",
                            url: Label.servePath + "/upload",
                            data: i,
                            processData: !1,
                            contentType: !1,
                            paramName: "file",
                            success: function(a) {
                                var t = e.getCursor();
                                e.replaceRange('<audio controls="controls" src="' + a.key + '"></audio>\n\n', CodeMirror.Pos(t.line, t.ch - Label.uploadingLabel.length), t)
                            },
                            error: function(a, t, o) {
                                alert("Error: " + o);
                                var i = e.getCursor();
                                e.replaceRange("", CodeMirror.Pos(i.line, i.ch - Label.uploadingLabel.length), i)
                            }
                        })
                    }
                },
                i.readAsDataURL(t)
            }
        }
    },
    setUnreadNotificationCount: function() {
        $.ajax({
            url: Label.servePath + "/notification/unread/count",
            type: "GET",
            cache: !1,
            success: function(e, a) {
                var t = function(e) {
                    var a = "",
                    t = '<span onclick="Util.makeNotificationRead(\'${markReadType}\');return false;" aria-label="' + Label.makeAsReadLabel + '" class="fn-right tooltipped tooltipped-nw"><svg height="18" viewBox="0 0 12 16" width="12">' + Label.checkIcon + "</svg></span>";
                    return e.unreadCommentedNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + '/notifications/commented">' + Label.notificationCommentedLabel + ' <span class="count">' + e.unreadCommentedNotificationCnt + "</span>" + t.replace("${markReadType}", "commented") + "</a></li>"),
                    e.unreadReplyNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + '/notifications/reply">' + Label.notificationReplyLabel + ' <span class="count">' + e.unreadReplyNotificationCnt + "</span>" + t.replace("${markReadType}", "reply") + "</a></li>"),
                    e.unreadAtNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + '/notifications/at">' + Label.notificationAtLabel + ' <span class="count">' + e.unreadAtNotificationCnt + "</span>" + t.replace("${markReadType}", "at") + "</a></li>"),
                    e.unreadFollowingNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + '/notifications/following">' + Label.notificationFollowingLabel + ' <span class="count">' + e.unreadFollowingNotificationCnt + "</span>" + t.replace("${markReadType}", "following") + "</a></li>"),
                    e.unreadPointNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + '/notifications/point">' + Label.pointLabel + ' <span class="count">' + e.unreadPointNotificationCnt + "</span></a></li>"),
                    e.unreadBroadcastNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + '/notifications/broadcast">' + Label.sameCityLabel + ' <span class="count">' + e.unreadBroadcastNotificationCnt + "</span></a></li>"),
                    e.unreadSysAnnounceNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + '/notifications/sys-announce">' + Label.systemLabel + ' <span class="count">' + e.unreadSysAnnounceNotificationCnt + "</span></a></li>"),
                    e.unreadNewFollowerNotificationCnt > 0 && (a += '<li><a href="' + Label.servePath + "/member/" + Label.currentUserName + '/followers">' + Label.newFollowerLabel + ' <span class="count">' + e.unreadNewFollowerNotificationCnt + "</span></a></li>"),
                    a
                },
                o = e.unreadNotificationCnt;
                if ($.ua.set(navigator.userAgent), $.ua.device.type && "mobile" === $.ua.device.type) {
                    if (0 < o) {
                        $("#aNotifications").removeClass("no-msg").addClass("msg").text(o).attr("href", "javascript:void(0)"),
                        0 === e.userNotifyStatus && window.localStorage.hadNotificate !== o.toString() && (Util.notifyMsg(o), window.localStorage.hadNotificate = o);
                        var i = t(e);
                        if (1 === $("#notificationsPanel").length) return $("#notificationsPanel ul").html(i),
                        !1;
                        $(".main:first").prepend('<div id="notificationsPanel" class="tab-current fn-clear fn-none"><ul class="tab fn-clear">' + i + "</ul></div>"),
                        $("#aNotifications").click(function() {
                            $("#notificationsPanel").slideToggle()
                        })
                    } else window.localStorage.hadNotificate = "false",
                    $("#aNotifications").removeClass("msg").addClass("no-msg").text(o).attr("href", Label.servePath + "/notifications");
                    return ! 1
                }
                if (0 < o) {
                    $("#aNotifications").removeClass("no-msg tooltipped tooltipped-w").addClass("msg").text(o).attr("href", "javascript:void(0)"),
                    0 === e.userNotifyStatus && window.localStorage.hadNotificate !== o.toString() && (Util.notifyMsg(o), window.localStorage.hadNotificate = o);
                    var i = t(e);
                    if (1 === $("#notificationsPanel").length) return $("#notificationsPanel ul").html(i),
                    !1;
                    $("#aNotifications").after('<div id="notificationsPanel" class="module person-list"><ul>' + i + "</ul></div>"),
                    $("#aNotifications").click(function() {
                        $("#notificationsPanel").show()
                    }),
                    $("body").click(function(e) {
                        "aNotifications" !== e.target.id && "notificationsPanel" !== $(e.target).closest(".module").attr("id") && $("#notificationsPanel").hide()
                    })
                } else window.localStorage.hadNotificate = "false",
                $("#notificationsPanel").remove(),
                $("#aNotifications").removeClass("msg").addClass("no-msg tooltipped tooltipped-w").text(o).attr("href", Label.servePath + "/notifications")
            }
        })
    },
    follow: function(e, a, t, o) {
        if (!Label.isLoggedIn) return Util.needLogin(),
        !1;
        if ($(e).hasClass("disabled")) return ! 1;
        var i = {
            followingId: a
        };
        $(e).addClass("disabled"),
        $.ajax({
            url: Label.servePath + "/follow/" + t,
            type: "POST",
            cache: !1,
            data: JSON.stringify(i),
            success: function(i, n) {
                i.sc && ($(e).removeClass("disabled"), "undefined" != typeof o ? "article" === t || "tag" === t ? $(e).html('<span class="icon-star"></span> ' + (o + 1)).attr("onclick", "Util.unfollow(this, '" + a + "', '" + t + "', " + (o + 1) + ")").attr("aria-label", Label.uncollectLabel).addClass("ft-red") : "article-watch" === t && $(e).html('<span class="icon-view"></span> ' + (o + 1)).attr("onclick", "Util.unfollow(this, '" + a + "', '" + t + "', " + (o + 1) + ")").attr("aria-label", Label.unfollowLabel).addClass("ft-red") : $(e).attr("onclick", "Util.unfollow(this, '" + a + "', '" + t + "')").text("article" === t ? Label.uncollectLabel: Label.unfollowLabel))
            },
            complete: function() {
                $(e).removeClass("disabled")
            }
        })
    },
    unfollow: function(e, a, t, o) {
        if ($(e).hasClass("disabled")) return ! 1;
        var i = {
            followingId: a
        };
        $(e).addClass("disabled"),
        $.ajax({
            url: Label.servePath + "/follow/" + t,
            type: "DELETE",
            cache: !1,
            data: JSON.stringify(i),
            success: function(i, n) {
                i.sc && ("undefined" != typeof o ? "article" === t || "tag" === t ? $(e).removeClass("ft-red").html('<span class="icon-star"></span> ' + (o - 1)).attr("onclick", "Util.follow(this, '" + a + "', '" + t + "'," + (o - 1) + ")").attr("aria-label", Label.collectLabel) : "article-watch" === t && $(e).removeClass("ft-red").html('<span class="icon-view"></span> ' + (o - 1)).attr("onclick", "Util.follow(this, '" + a + "', '" + t + "'," + (o - 1) + ")").attr("aria-label", Label.followLabel) : $(e).attr("onclick", "Util.follow(this, '" + a + "', '" + t + "')").text("article" === t ? Label.collectLabel: Label.followLabel))
            },
            complete: function() {
                $(e).removeClass("disabled")
            }
        })
    },
    goTop: function() {
        $("html, body").animate({
            scrollTop: 0
        },
        800)
    },
    goLogin: function() {
        if ( - 1 === location.href.indexOf("/login")) {
            var e = location.href;
            0 === location.search.indexOf("?goto") && (e = location.href.replace(location.search, "")),
            window.location.href = Label.servePath + "/login?goto=" + encodeURIComponent(e)
        }
    },
    needLogin: function() {
        Util.goLogin()
    },
    goRegister: function() {
        if ( - 1 === location.href.indexOf("/register")) {
            var e = location.href;
            0 === location.search.indexOf("?goto") && (e = location.href.replace(location.search, "")),
            window.location.href = Label.servePath + "/register?goto=" + encodeURIComponent(e)
        }
    },
    _kill: function() {
        "IE" === $.ua.browser.name && parseInt($.ua.browser.version) < 10 && $.ajax({
            url: Label.servePath + "/kill-browser",
            type: "GET",
            cache: !1,
            success: function(e, a) {
                $("body").append(e),
                $("#killBrowser").dialog({
                    modal: !0,
                    hideFooter: !0,
                    height: 345,
                    width: 600
                }),
                $("#killBrowser").dialog("open")
            }
        })
    },
    _initActivity: function() {
        var e = $(".person-info"),
        a = e.data("percent"),
        t = 0,
        o = 0,
        i = 0;
        a <= 25 ? t = parseInt(a / .25) : a <= 75 ? (t = 100, o = parseInt((a - 25) / 2 / .25)) : a <= 100 && (t = 100, o = 100, i = parseInt((a - 75) / .25)),
        e.find(".bottom").css({
            width: t + "%",
            left: (100 - t) / 2 + "%"
        }),
        e.find(".top-left").css({
            width: parseInt(i / 2) + "%",
            left: 0
        }),
        e.find(".top-right").css({
            width: parseInt(i / 2) + "%",
            right: 0
        }),
        e.find(".left").css({
            height: o + "%",
            top: 100 - o + "%"
        }),
        e.find(".right").css({
            height: o + "%",
            top: 100 - o + "%"
        })
    },
    init: function(e) {
        return this._kill(),
        this._initNav(),
        this._initActivity(),
        1 === $(".pagination select").length && $(".pagination select").change(function() {
            var e = $(this).data("url") + "?p=" + $(this).val();
            $(this).data("param") && (e += "&" + $(this).data("param")),
            window.location.href = e
        }),
        $(".nav .icon-search").click(function() {
            $(".nav input.search").focus()
        }),
        $(".nav input.search").focus(function() {
            $(".nav .tags").css("visibility", "hidden")
        }).blur(function() {
            $(".nav .tags").css("visibility", "visible")
        }),
        $(window).scroll(function() {
            $(window).scrollTop() > 20 ? $(".go-top").show() : $(".go-top").hide()
        }),
        e && (window.localStorage.hadNotificate || (window.localStorage.hadNotificate = "false"), Util.setUnreadNotificationCount()),
        this._initCommonHotKey(),
        console.log("%cCopyright © 2012-%s, b3log.org & hacpai.com\n\n%cHacPai%c 平等、自由、奔放\n\n%cFeel easy about trust.", "font-size:12px;color:#999999;", (new Date).getFullYear(), 'font-family: "Helvetica Neue", "Luxi Sans", "DejaVu Sans", Tahoma, "Hiragino Sans GB", "Microsoft Yahei", sans-serif;font-size:64px;color:#404040;-webkit-text-fill-color:#404040;-webkit-text-stroke: 1px #777;', 'font-family: "Helvetica Neue", "Luxi Sans", "DejaVu Sans", Tahoma, "Hiragino Sans GB", "Microsoft Yahei", sans-serif;font-size:12px;color:#999999; font-style:italic;', 'font-family: "Helvetica Neue", "Luxi Sans", "DejaVu Sans", Tahoma, "Hiragino Sans GB", "Microsoft Yahei", sans-serif;font-size:12px;color:#999999;'),
        !e && void $("body").click(function(e) {
            0 === $(e.target).closest(".nav .form").length && $(".nav .form").hide()
        })
    },
    initUserChannel: function(e) {
        var a = new ReconnectingWebSocket(e);
        a.reconnectInterval = 1e4,
        a.onopen = function() {
            setInterval(function() {
                a.send("-hb-")
            },
            3e5)
        },
        a.onmessage = function(e) {
            var a = JSON.parse(e.data);
            switch (a.command) {
            case "refreshNotification":
                Util.setUnreadNotificationCount()
            }
        },
        a.onclose = function() {
            a.close()
        },
        a.onerror = function(e) {
            console.log("ERROR", e)
        }
    },
    _initNav: function() {
        var e = location.href;
        $(".user-nav > a").each(function() {
            0 === e.indexOf($(this).attr("href")) ? $(this).addClass("selected") : "/register" === location.pathname ? $(".user-nav a:last").addClass("selected") : "/login" === location.pathname ? $(".user-nav a:first").addClass("selected") : 0 !== e.indexOf(Label.servePath + "/settings") && 0 !== e.indexOf($("#aPersonListPanel").data("url")) || $("#aPersonListPanel").addClass("selected")
        }),
        $(".nav .avatar-small").parent().click(function() {
            $("#personListPanel").show()
        }),
        $("body").click(function(e) {
            "aPersonListPanel" !== $(e.target).closest("a").attr("id") && "personListPanel" !== $(e.target).closest(".module").attr("id") && $("#personListPanel").hide()
        }),
        1 === $(".nav-tabs a:last").length && $(".nav-tabs a:last")[0].offsetTop > 0 && $(".nav-tabs").mouseover(function() {
            $(".user-nav").hide()
        }).mouseout(function() {
            $(".user-nav").show()
        })
    },
    logout: function() {
        window.localStorage && (window.localStorage.clear(), window.localStorage.hadNotificate = "false"),
        window.location.href = Label.servePath + "/logout?goto=" + Label.servePath
    },
    startsWith: function(e, a) {
        return e.match("^" + a) == a
    },
    uploadFile: function(e) {
        var a = "",
        t = !1;
        return "" === e.qiniuUploadToken ? ($("#" + e.id).fileupload({
            multipart: !0,
            pasteZone: e.pasteZone,
            dropZone: e.pasteZone,
            url: Label.servePath + "/upload",
            paramName: "file",
            add: function(a, o) {
                if (window.File && window.FileReader && window.FileList && window.Blob) {
                    var i = new FileReader;
                    i.readAsArrayBuffer(o.files[0]),
                    i.onload = function(a) {
                        var i = new Uint8Array(a.target.result.slice(0, 11));
                        return t = isImage(i),
                        t && a.target.result.byteLength > e.imgMaxSize ? void alert("This image is too large (max " + e.imgMaxSize / 1024 / 1024 + "M)") : !t && a.target.result.byteLength > e.fileMaxSize ? void alert("This file is too large (max " + e.fileMaxSize / 1024 / 1024 + "M)") : void o.submit()
                    }
                } else o.submit()
            },
            formData: function(e) {
                var a = e.serializeArray();
                return a
            },
            submit: function(a, t) {
                if (e.editor.replaceRange) {
                    var o = e.editor.getCursor();
                    e.editor.replaceRange(e.uploadingLabel, o, o)
                } else $("#" + e.id + " input").prop("disabled", !0)
            },
            done: function(o, i) {
                var n = i.result.key;
                if (!n) return void alert("Upload error");
                if (e.editor.replaceRange) {
                    var r = e.editor.getCursor();
                    a = i.result.name,
                    t ? e.editor.replaceRange("![" + a + "](" + n + ") \n\n", CodeMirror.Pos(r.line, r.ch - e.uploadingLabel.length), r) : e.editor.replaceRange("[" + a + "](" + n + ") \n\n", CodeMirror.Pos(r.line, r.ch - e.uploadingLabel.length), r)
                } else e.editor.$it.val(e.editor.$it.val() + "![" + a + "](" + n + ") \n\n"),
                $("#" + e.id + " input").prop("disabled", !1)
            },
            fail: function(a, t) {
                if (alert("Upload error: " + t.errorThrown), e.editor.replaceRange) {
                    var o = e.editor.getCursor();
                    e.editor.replaceRange("", CodeMirror.Pos(o.line, o.ch - e.uploadingLabel.length), o)
                } else $("#" + e.id + " input").prop("disabled", !1)
            }
        }).on("fileuploadprocessalways",
        function(e, a) {
            var t = a.files[a.index];
            a.files.error && t.error && alert(t.error)
        }), !1) : void $("#" + e.id).fileupload({
            multipart: !0,
            pasteZone: e.pasteZone,
            dropZone: e.pasteZone,
            url: "https://up.qbox.me/",
            paramName: "file",
            add: function(o, i) {
                if (i.files[0].name) {
                    var n = i.files[0].name.match(/[a-zA-Z0-9.]/g).join("");
                    a = getUUID() + "-" + n,
                    "" === n.split(".")[0] && (a = getUUID() + n)
                } else a = getUUID() + "." + i.files[0].type.split("/")[1];
                if (window.File && window.FileReader && window.FileList && window.Blob) {
                    var r = new FileReader;
                    r.readAsArrayBuffer(i.files[0]),
                    r.onload = function(a) {
                        var o = new Uint8Array(a.target.result.slice(0, 11));
                        return t = isImage(o),
                        t && a.target.result.byteLength > e.imgMaxSize ? void alert("This image is too large (max " + e.imgMaxSize / 1024 / 1024 + "M)") : !t && a.target.result.byteLength > e.fileMaxSize ? void alert("This file is too large (max " + e.fileMaxSize / 1024 / 1024 + "M)") : void i.submit()
                    }
                } else i.submit()
            },
            formData: function(t) {
                var o = t.serializeArray();
                return o.push({
                    name: "key",
                    value: "file/" + (new Date).getFullYear() + "/" + ((new Date).getMonth() + 1) + "/" + a
                }),
                o.push({
                    name: "token",
                    value: e.qiniuUploadToken
                }),
                o
            },
            submit: function(a, t) {
                if (e.editor.replaceRange) {
                    var o = e.editor.getCursor();
                    e.editor.replaceRange(e.uploadingLabel, o, o)
                } else $("#" + e.id + " input").prop("disabled", !1)
            },
            done: function(o, i) {
                var n = i.result.key;
                if (!n) return void alert("Upload error");
                if (e.editor.replaceRange) {
                    var r = e.editor.getCursor();
                    t ? e.editor.replaceRange("![" + a + "](" + e.qiniuDomain + "/" + n + ") \n\n", CodeMirror.Pos(r.line, r.ch - e.uploadingLabel.length), r) : e.editor.replaceRange("[" + a + "](" + e.qiniuDomain + "/" + n + ") \n\n", CodeMirror.Pos(r.line, r.ch - e.uploadingLabel.length), r)
                } else e.editor.$it.val("![" + a + "](" + e.qiniuDomain + "/" + n + ") \n\n"),
                $("#" + e.id + " input").prop("disabled", !1)
            },
            fail: function(a, t) {
                if (alert("Upload error: " + t.errorThrown), e.editor.replaceRange) {
                    var o = e.editor.getCursor();
                    e.editor.replaceRange("", CodeMirror.Pos(o.line, o.ch - e.uploadingLabel.length), o)
                } else $("#" + e.id + " input").prop("disabled", !1)
            }
        }).on("fileuploadprocessalways",
        function(e, a) {
            var t = a.files[a.index];
            a.files.error && t.error && alert(t.error)
        })
    },
    mouseClickEffects: function() {
        var e = 0;
        jQuery(document).ready(function(a) {
            a("html").click(function(t) {
                var o, i = 18;
                e++,
                1 == e ? o = a("<b></b>").text("OωO") : 2 === e ? o = a("<b></b>").text("☆") : 3 === e ? o = a("<b></b>").text("❤") : 4 === e ? o = a("<b></b>").text("☆") : 5 === e ? o = a("<b></b>").text("☆") : 6 === e ? o = a("<b></b>").text("❤") : 7 === e ? o = a("<b></b>").text("☺") : 8 === e ? o = a("<b></b>").text("｡><｡") : 10 === e ? o = a("<b></b>").text("☆") : e >= 20 && e <= 105 ? o = a("<b></b>").text("❤") : (o = a("<i class='icon-heart'></i>"), i = Math.round(14 * Math.random() + 6));
                var n = t.pageX,
                r = t.pageY;
                o.css({
                    "z-index": 9999,
                    top: r - 20,
                    left: n,
                    position: "absolute",
                    color: "#E94F06",
                    "font-size": i,
                    "-moz-user-select": "none",
                    "-webkit-user-select": "none",
                    "-ms-user-select": "none"
                }),
                a("body").append(o),
                o.animate({
                    top: r - 180,
                    opacity: 0
                },
                1500,
                function() {
                    o.remove()
                })
            })
        })
    }
},
Validate = {
    goValidate: function(e) {
        for (var a = "<ul>",
        t = 0; t < e.data.length; t++) Validate.validate(e.data[t]) || (a += "<li>" + e.data[t].msg + "</li>");
        return "<ul>" === a ? (e.target.html(""), e.target.removeClass("error"), !0) : (e.target.html(a + "</ul>"), e.target.addClass("error"), !1)
    },
    validate: function(e) {
        var a = !0,
        t = "";
        switch (t = "editor" === e.type ? e.target.getValue() : "imgSrc" === e.type ? e.target.attr("src") : "imgStyle" === e.type ? e.target.data("imageurl") : e.target.val().toString().replace(/(^\s*)|(\s*$)/g, ""), e.type) {
        case "email":
            /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(e.target.val()) || (a = !1);
            break;
        case "password":
            (0 === e.target.val().length || e.target.val().length > 16) && (a = !1);
            break;
        case "confirmPassword":
            e.target.val() !== e.original.val() && (a = !1);
            break;
        case "tags":
            var o = t.split(","); ("" === t || o.length > 7) && (a = !1);
            for (var i = 0; i < o.length; i++) if ("" === o[i].replace(/(^\s*)|(\s*$)/g, "") || o[i].replace(/(^\s*)|(\s*$)/g, "").length > 50) {
                a = !1;
                break
            }
            break;
        case "url":
        case "imgSrc":
        case "imgStyle":
            ("" === t || "" !== t && (!/^\w+:\/\//.test(t) || t.length > 100)) && (a = !1);
            break;
        default:
            a = t.length <= e.max && t.length >= (e.min ? e.min: 0)
        }
        return a
    }
},
Label = {},
pngMagic = [137, 80, 78, 71, 13, 10, 26, 10],
jpeg_jfif = [74, 70, 73, 70],
jpeg_exif = [69, 120, 105, 102],
jpegMagic = [255, 216, 255, 224],
gifMagic0 = [71, 73, 70, 56, 55, 97],
getGifMagic1 = [71, 73, 70, 56, 57, 97],
wavMagic1 = [82, 73, 70, 70],
wavMagic2 = [87, 65, 86, 69],
Audio = {
    availabel: !1,
    wavFileBlob: null,
    recorderObj: null,
    init: function(e) {
        function a(e) {
            console.log("getUserMedia->failure(): ERROR: Microphone access request failed!");
            var a, t = "PermissionDeniedError",
            o = "DevicesNotFoundError";
            switch (e.name) {
            case t:
                a = Label.recordDeniedLabel;
                break;
            case o:
                a = Label.recordDeviceNotFoundLabel;
                break;
            default:
                a = "ERROR: The following unexpected error occurred while attempting to connect to your microphone: " + e.name
            }
        }
        function t(a) {
            var t = 2048,
            o = PredefinedRecordingModes.MONO_5_KHZ,
            i = o.getSampleRate(),
            n = o.getChannelCount(),
            r = new BrowserWindowAudioContextDetection;
            if (!r.windowAudioContextSupported()) {
                var l = "Unable to detect window audio context, cannot continue.";
                return void console.log("getUserMedia->success(): " + l)
            }
            var s = r.getWindowAudioContextMethod();
            Audio.recorderObj = new SoundRecorder(s, t, i, n),
            Audio.recorderObj.init(a),
            Audio.recorderObj.recorder.onaudioprocess = function(e) {
                if (Audio.recorderObj.isRecording()) {
                    var a = e.inputBuffer.getChannelData(0),
                    t = e.inputBuffer.getChannelData(1);
                    Audio.recorderObj.cloneChannelData(a, t)
                }
            },
            Audio.availabel = !0,
            e && e()
        }
        var o = new BrowserGetUserMediaDetection;
        o.getUserMediaSupported() ? (navigator.getUserMedia = o.getUserMediaMethod(), navigator.getUserMedia({
            audio: !0
        },
        t, a)) : console.log("ERROR: getUserMedia not supported by browser.")
    },
    handleStartRecording: function() {
        Audio.recorderObj.startRecordingNewWavFile()
    },
    handleStopRecording: function() {
        Audio.recorderObj.stopRecording(),
        Audio.wavFileBlob = Audio.recorderObj.buildWavFileBlob()
    }
};