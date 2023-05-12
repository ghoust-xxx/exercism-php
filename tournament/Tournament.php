<?php

declare(strict_types=1);

function cmp($a, $b) {
    if (($a["P"] ?? 0) > ($b["P"] ?? 0)) {
        return -1;
    } else if (@$a["P"] < @$b["P"]) {
        return 1;
    } else {
        return strcmp($a["name"], $b["name"]);
    }
}

class Tournament
{
    public function __construct()
    {
    }

    function tally($in) {
        $out = sprintf("%-30s |%3s |%3s |%3s |%3s |%3s",
            "Team", "MP", "W", "D", "L", "P");
        if (strcmp($in, "") == 0) {
            return $out;
        };

        $data = str_getcsv($in, "\n");
        $res = [];
        foreach($data as &$row) {
            $row = str_getcsv($row, ";");
            @$res[$row[0]]["MP"]++;
            @$res[$row[0]]["name"] = $row[0];
            @$res[$row[1]]["MP"]++;
            @$res[$row[1]]["name"] = $row[1];
            switch ($row[2]) {
                case "win":
                    @$res[$row[0]]["W"]++;
                    $res[$row[0]]["P"] = @$res[$row[0]]["P"] + 3;
                    @$res[$row[1]]["L"]++;
                    break;
                case "loss":
                    @$res[$row[1]]["W"]++;
                    $res[$row[1]]["P"] = @$res[$row[1]]["P"] + 3;
                    @$res[$row[0]]["L"]++;
                    break;
                case "draw":
                    @$res[$row[0]]["D"]++;
                    @$res[$row[1]]["D"]++;
                    @$res[$row[0]]["P"]++;
                    @$res[$row[1]]["P"]++;
                    break;
            };
        }
        usort($res, "cmp");
        foreach ($res as $team => $val) {
            $out .= sprintf("\n%-30s |%3s |%3s |%3s |%3s |%3s",
                $val["name"], $val["MP"] ?? 0, @$val["W"] ?? 0, @$val["D"] ?? 0,
                @$val["L"] ?? 0, @$val["P"] ?? 0);
        }
        return $out;
    }
}
