<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Sudoku Solver</title>

</head>

<body>

    <?php


    function isValid($grid, $row, $col, $num)
    {
        for ($x = 0; $x < 9; $x++) {
            if ($grid[$row][$x] == $num || $grid[$x][$col] == $num) {
                return false;
            }
        }

        $startRow = $row - $row % 3;
        $startCol = $col - $col % 3;
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if ($grid[$i + $startRow][$j + $startCol] == $num) {
                    return false;
                }
            }
        }

        return true;
    }


    function solveSudoku(&$grid)
    {
        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                if ($grid[$row][$col] == 0) {
                    for ($num = 1; $num <= 9; $num++) {
                        if (isValid($grid, $row, $col, $num)) {
                            $grid[$row][$col] = $num;

                            if (solveSudoku($grid)) {
                                return true;
                            }

                            $grid[$row][$col] = 0;
                        }
                    }

                    return false;
                }
            }
        }

        return true;
    }


    $grid = array_fill(0, 9, array_fill(0, 9, 0));

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        for ($row = 0; $row < 9; $row++) {
            for ($col = 0; $col < 9; $col++) {
                $input_name = "cell_" . $row . "_" . $col;
                $grid[$row][$col] = isset($_POST[$input_name]) && is_numeric($_POST[$input_name]) ? (int)$_POST[$input_name] : 0;
            }
        }


        solveSudoku($grid);
    }
    ?>

    <div class="container-fluid">
        <div class="row">
            <h1 class="text-center mt-5">Sudoku Solver</h1>
            <div class="col d-flex justify-content-center">
                <form method="POST">
                    <table class="mt-5">
                        <?php

                        for ($row = 0; $row < 9; $row++) {
                            echo "<tr>";
                            for ($col = 0; $col < 9; $col++) {
                                $value = $grid[$row][$col] == 0 ? "" : $grid[$row][$col];
                                echo "<td><input type='text' name='cell_{$row}_{$col}' maxlength='1' value='$value'></td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </table>
                    <br>
                    <button class="btn btn-success" type="submit">Solve</button>
                    <div class="btn btn-danger" id="reset">Reset</div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function clearCells() {
            const inputs = document.querySelectorAll("input");
            inputs.forEach(input => input.value = "");

        }
        document.getElementById("reset").addEventListener("click", clearCells);
    </script>
</body>
<style>
    table {
        border-collapse: collapse;
        width: 300px;
        height: 300px;
        text-align: center;
    }

    td {
        border: 1px solid black;
        width: 33px;
        height: 33px;
        font-size: 20px;
    }

    input {
        width: 80%;
        height: 100%;
        text-align: center;
        font-size: 20px;
        border: none;
    }
</style>

</html>