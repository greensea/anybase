<?php
class AnyBase {
    /**
     * 传入编号和基数列表，返回分解出来的基数编号
     */
    public static function  extract(int $id, array $_cardinals) : array{
        $cardinals = array_values($_cardinals);

        while (!empty($cardinals)) {
            list($bit, $base) = self::extractMSB($id, $cardinals);
            $ret[] = $bit;

            // printf("Input %d, %s, output %d, %d\n", $id, json_encode($cardinals), $bit, $base);

            array_shift($cardinals);

            $id = $id - $base * $bit;
        }

        return $ret;
    }

    /**
     * 根据传入的 id 和基数列表，获取传入的 id 的最高位以及底数。
     * 例如，传入 234 和 [10, 10, 10]，则返回 [2, 100]
     * 
     * @return [MSB, base]
     */
    public static function extractMSB($id, array $cardinals) {
        $len = count($cardinals);
        switch ($len) {
            case 0:
                throw new Exception("Parameter cardinals is empty");
                break;
            case 1:
                return [$id, 1];
                break;
            default:
                $vals = array_values($cardinals);
                $base = 1;
                for ($i = 1; $i < count($vals); $i++) {
                    $base *= $vals[$i];
                }
                return [floor($id / $base), $base];
                break;
        }
    }

    /**
     * 传入每一位的列表和基数列表，返回编号
     */
    public static function convert(array $ids, array $cardinals) : int {
        if (count($cardinals) != count($ids)) {
            throw new Exception('Elements number in cardianls and ids is different');
        }

        $id = 0;

        $base = 1;
        for ($i = count($ids) - 1; $i >= 0; $i--) {
            $bit = $ids[$i];
            $cardinal = $cardinals[$i];

            $id += ($bit * $base);
            $base *= $cardinal;
        }

        return $id;
    }
}

