<?php

class Chess
{
    private $field, $figure;
    private $arr_figure = ['p' => 'pawn', 'r' => 'rook', 'h' => 'horse', 'b' => 'bishop', 'q' => 'queen', 'k' => 'king'];
    private $arr_row = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3, 'e' => 4, 'f' => 5, 'g' => 6, h=>7];
    public function initial_chess()
    {
        for ($i=1; $i<=8; $i++){
            for ($j=0; $j<=7; $j++){
                $this->field[$i][$j] = 'e';
            }
        }
    }

    public function print_field()
    {
        $count = 0;
        print "  a b c d e f g h \n";
        for ($i = 8; $i >= 1; $i--) {
            print $i . " ";
            for ($j = 0; $j < 8; $j++) {
                if ($this->field[$i][$j] != 'e') $count++;
                print $this->field[$i][$j] . " ";
            }
            print $i . "\n";
        }
        print "  a b c d e f g h \n";
        if ($count != 0) printf("You have %d figures on the field\n", $count);
    }

    public function save_field(){
        $file = fopen("field.txt", "w");
        $title = "  a b c d e f g h \n";
        fwrite($file, $title);
        for ($i = 8; $i >= 1; $i--) {
            fwrite($file, $i . " ");
            for ($j = 0; $j < 8; $j++) {
                fwrite($file,$this->field[$i][$j] . " ");
            }
            fwrite($file,$i . "\n");
        }
        fwrite($file, $title);
        fclose($file);
    }

    public function read_file(){
        $i = 8;
        $file = fopen('field.txt', 'r');
        while (!feof($file)) {
            $str = fgets($file);
            $str = str_replace(" ", "", $str);
            if (preg_match_all("/\d(\w{8})\d/", $str, $out, PREG_SET_ORDER)) {
                $parse_str = $out[0][1];
                for($j=0; $j<8; $j++) {
                    $this->field[$i][$j] = $parse_str[$j];
                }
                $i--;
                //print $out[0][1] . "\n";
            }
        }
    }

    public function add_figure($figure, $row, $str){

        if ($this->field[$str][$this->arr_row[$row]] == 'e'){
            $this->field[$str][$this->arr_row[$row]] = $figure;
            print "You put " . $this->arr_figure[$figure] . " on the " . $row . $str . "\n";
        }
        else {
            print "this point is don't empty\n";
        }
    }

    public function move_figure($row, $str){
        if ($row && $str ) {
            if ($this->field[$str][$this->arr_row[$row]] != 'e') {
                $this->figure = $this->field[$str][$this->arr_row[$row]];
                printf("You take : %s\n", $this->arr_figure[$this->figure]);
                $this->field[$str][$this->arr_row[$row]] = 'e';
            }
            else print "this point is empty\n";
        }
        else print "incorrect input\n";
    }

    public function delete_figure($row, $str)
    {
        $arr_row = ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 3, 'e' => 4, 'f' => 5, 'g' => 6, h => 7];
        if ($row && $str ) {
            if ($this->field[$str][$arr_row[$row]] != 'e') {
                $this->field[$str][$arr_row[$row]] = 'e';
                print "this figure are deleted\n";
            }
            else print "this point is empty\n";
        }
        else print "incorrect input\n";
    }


    public function getFigure(){
        return $this->figure;
    }
}

$chess = new Chess();

$chess->initial_chess();



$command = true;
while($command){
    print "What you want doing?
    1 - print field
    2 - save field
    3 - add figure
    4 - move figure
    5 - delete figure
    6 - loading save field 
    0 - exit\n";
    fscanf(STDIN, "%d\n", $command);
    switch ($command){
        case 1:
            $chess->print_field();
            break;
        case 2:
            $chess->save_field();
            break;
        case 3:
            $figure = "";
            $flagFigure = true;
            $chess->print_field();
            while($flagFigure) {
                print "Choose type of figure:\n p - pawn\n r - rook\n h - horse\n b - bishop\n q - queen\n k - king\n";
                fscanf(STDIN, "%s", $figure);
                if ($figure == 'p' || $figure == 'r' || $figure == 'h' || $figure == 'b' || $figure == 'q' || $figure == 'k')
                    $flagFigure = false;
                else
                    print "incorrect input\n";
            }
            print "Choose place: ";
            fscanf(STDIN, "%1s%d\n", $row, $str);
            $chess->add_figure($figure, $row, $str);
            break;
        case 4:
            $chess->print_field();
            print "What kind figure do you would like move? Choose his position:\n";
            fscanf(STDIN, "%1s%d\n", $row, $str);
            $chess->move_figure($row, $str);
            print "choose new place: ";
            fscanf(STDIN, "%1s%d\n", $newrow, $newstr);
            $figure = $chess->getFigure();
            $chess->add_figure($figure, $newrow, $newstr);
            break;
        case 5:
            $chess->print_field();
            print "What kind figure do you would like delete?\n";
            fscanf(STDIN, "%1s%d\n", $row, $str);
            $chess->delete_figure($row, $str);
            break;
        case 6:
            $chess->read_file();
            $chess->print_field();
            print "Load chess field are completed\n";
            break;
        case 0:
            $command = false;
            break;
        default:
            print "Incorrect command\n";
    }
}
?>
