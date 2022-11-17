<?php

function operators($char)
{
    if ($char === "/" || $char === "*" || $char === "%") {
        return 2;
    } else if ($char === "+" || $char === "-") {
        return 1;
    } else {
        return 0;
    }
}

function intfix_to_postfix($expr)
{
    $stack = [];
    $result = [];
    $arr_expr = preg_split("/\b|(?<=\W)(?=\W)/", $expr, -1, PREG_SPLIT_NO_EMPTY);
    for ($i = 0; $i < count($arr_expr); $i++) {
            switch ($arr_expr[$i]) {
            case "(":
                $stack[] = $arr_expr[$i];
                break;
            case ")":
                while (end($stack) !== "(") {
                    $result[] = array_pop($stack);
                }
                array_pop($stack);
                break;
            case "+":
            case "-":
            case "*":
            case "/":
            case "%":
                if (count($stack) === 0) {
                    $stack[] = $arr_expr[$i];
                } else {
                    if (operators(end($stack)) > operators($arr_expr[$i])) {
                        $result[] = array_pop($stack);
                    }
                    $stack[] = $arr_expr[$i];
                }
                break;
            default:
                $result[] = $arr_expr[$i];
                break;
        }
    }
    while (!empty($stack)) {
        $result[] = array_pop($stack);
    }
    return $result;
}

function eval_expr($expr)
{
    $arr_expr = intfix_to_postfix($expr);
    for ($i = 0; $i < count($arr_expr); $i++) {
        var_dump($arr_expr);  
        if (is_numeric($arr_expr[$i])) {
            continue;
        }
        if (count($arr_expr) <= 2){
            break;
        }     
        if (isset($arr_expr[$i + 1]) && $arr_expr[$i] === "-" && $arr_expr[$i + 1] === $arr_expr[$i]){
            $arr_expr[$i] = "+";
            unset($arr_expr[$i + 1]);
        }
        switch ($arr_expr[$i]) {
            case "+":
                $arr_expr[$i] = (float)$arr_expr[$i - 2] + (float)$arr_expr[$i - 1];
                break;
            case "-":
                $arr_expr[$i] = (float)$arr_expr[$i - 2] - (float)$arr_expr[$i - 1];
                break;
            case "*":
                $arr_expr[$i] = (float)$arr_expr[$i - 2] * (float)$arr_expr[$i - 1];
                break;
            case "/":
                $arr_expr[$i] = (float)$arr_expr[$i - 2] / (float)$arr_expr[$i - 1];
                break;
            case "%":
                $arr_expr[$i] = (float)$arr_expr[$i - 2] % (float)$arr_expr[$i - 1];
                break;
        }
        unset($arr_expr[$i - 2], $arr_expr[$i - 1]);
        $arr_expr = array_values($arr_expr);
        $i = 0;
    }
    //eval("echo 'resultat attendu : '," . $expr . " . PHP_EOL;");
    return $arr_expr[0] . PHP_EOL;
}
