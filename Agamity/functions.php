<?php

function getLoLMatchDetails($match_code) {
    global $_RIOTAPI;
    $url = "https://americas.api.riotgames.com/lol/tournament/v5/games/by-code/$match_code?api_key=$_RIOTAPI";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

    $response = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($response, true);
    
    return $data;
}

function checkIfRiotAccountExistsByPuuid($puuid) {
    global $conn;
    
    $sql = mysqli_query($conn, "SELECT user_id FROM table_name WHERE puuid = '$puuid'");
    if (mysqli_num_rows($sql) != 0) {
        while ($row = mysqli_fetch_array($sql)) {
            $user_id = $row['user_id'];
            return $user_id;
        }
    } else {
        return false;
    }
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

function getMatchTeamInfo($puuid, $userSummonerLevel, $userSummonerName, $championName, $champLevel, $MVPScoreNum, $direction) {
    $checkIfRiotAccountExistsByPuuid = checkIfRiotAccountExistsByPuuid($puuid);
    if ($checkIfRiotAccountExistsByPuuid == false) {
        $anchor_div_start = $anchor_div_end = '';
        $userImgDiv = '
            <div class="b_255_005 b_r_50perc flex_row flex_center min_w_30px w_30px h_30px">
                <img src="https://www.agamity.com/user_555555_svg.svg" alt="" class="w_50">
            </div>
        ';
        $userNameDiv = '
            <p class="w_fit-content d_ltr text_16 f_w_600 text_777">
                Empty
            </p>
        ';
    } else {
        $user_id = $checkIfRiotAccountExistsByPuuid;
        $userInfoArray = getUserInfoBasic($user_id);
        $nicknameUsers = $userInfoArray[0];
        $profile_img = $userInfoArray[1];
        $anchor_div_start = '<a href="https://www.agamity.com/user/'.$user_id.'" onclick="event.stopPropagation();" class="t_d_none">';
        $anchor_div_end = '</a>';
        $userImgDiv = '
            <div class="h_t_s_03 b_255_005 min_w_30px w_30px h_30px flex_row flex_center b_r_50perc o_hidden">
                '.$profile_img.'
            </div>
        ';
        $userNameDiv = '
            <p class="w_fit-content d_ltr h_text_scroll text_overflow">
                <span class="d_ltr text_16 h_c_blue f_w_600">
                    '.$nicknameUsers.'
                </span>
            </p>
        ';
    }
    if ($MVPScoreNum == 1) {
        $MVPScoreDiv = '
            <div class="p_2_10 b_d4af37_01 b_r_5 w_fit-content">
                <p class="text_12 text_d4af37 f_w_600">MVP</p>
            </div>
        ';
    } else {
        $MVPScoreDiv = '
            <div class="p_2_10 b_255_005 b_r_5 w_fit-content">
                <p class="text_12 text_777 f_w_600">'.ordinal($MVPScoreNum).'</p>
            </div>
        ';
    }
    return '
        <div class="max_w_100 flex_row flex_gap_10 a_i_center '.$direction.'">
            <div class="hover p_relative">
                <img src="https://www.agamity.com/lol_champions/champion_small_img/'.$championName.'.png" alt="" class="w_50px h_50px min_w_50px b_r_10">
                <div class="expand_3_09-50 hover_small_text w_s_nowrap">
                    <t></t>
                    '.$championName.'
                </div>
            </div>
            <div class="max_w_100-60 flex_column">
                <div class="max_w_100 a_i_center flex_row flex_gap_10">
                    '.$anchor_div_start.'
                        '.$userImgDiv.'
                    '.$anchor_div_end.'
                    <div class="max_w_100-40 flex_column">
                        '.$anchor_div_start.'
                            '.$userNameDiv.'
                        '.$anchor_div_end.'
                        <div class="flex_row a_i_center flex_gap_5">
                            <div class="hover p_relative">
                                <div class="flex_row flex_center w_fit-content b_r_20 p_0_5 b_1_solid_20a4f3">
                                    <p class="w_s_nowrap text_10 t_a_capitalize text_20A4F3">SL '.$userSummonerLevel.'</p>
                                </div>
                                <div class="expand_3_09-50 hover_small_text w_s_nowrap">
                                    <t></t>
                                    Summoner Level
                                </div>
                            </div>
                            <p class="text_16">'.$MVPScoreDiv.'</p>
                        </div>
                    </div>
                </div>
                <p class="text_12 f_w_600 h_text_scroll text_overflow">
                    <span class="text_12 text_eee f_w_600">
                        <span class="text_777">Playing as</span> '.$userSummonerName.'
                    </span>
                </p>
            </div>
        </div>
    ';
}
