<?php

class Chess
{
    public $count = 0;
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
        $this->count = 0;
        print "  a b c d e f g h \n";
        for ($i = 8; $i >= 1; $i--) {
            print $i . " ";
            for ($j = 0; $j < 8; $j++) {
                if ($this->field[$i][$j] != 'e') $this->count++;
                print $this->field[$i][$j] . " ";
            }
            print $i . "\n";
        }
        print "  a b c d e f g h \n";
        if ($this->count != 0) printf("You have %d figures on the field\n", $this->count);
    }

    public function save_field(){
        $file = fopen("field.tt", "w");
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
        $filename = "field.txt";
        $file = fopen($filename, 'r');
        if ($file){
            while (!feof($file)) {
                $str = fgets($file);
                $str = str_replace(" ", "", $str);
                if (preg_match_all("/\d(\w{8})\d/", $str, $out, PREG_SET_ORDER)) {
                    $parse_str = $out[0][1];
                    for ($j = 0; $j < 8; $j++) {
                        $this->field[$i][$j] = $parse_str[$j];
                    }
                    $i--;
                }
            }
            $this->print_field();
            printf("File %s are opened\n", $filename);
        }
        else {
            printf("File %s not found\n", $filename);
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

    public function move_figure(){
        $this->print_field();
        print "What kind figure do you would like move? Choose his position:\n";
        fscanf(STDIN, "%1s%d\n", $row, $str);
        if ($row && $str ) {
            if ($this->field[$str][$this->arr_row[$row]] != 'e') {
                $this->figure = $this->field[$str][$this->arr_row[$row]];
                printf("You take : %s\n", $this->arr_figure[$this->figure]);
                $this->field[$str][$this->arr_row[$row]] = 'e';
                print "choose new place: ";
                fscanf(STDIN, "%1s%d\n", $newrow, $newstr);
                $this->add_figure($this->figure, $newrow, $newstr);
            }
            else print "this point is empty\n";
        }
        else print "incorrect input\n";
    }

    public function delete_figure()
    {
        $this->print_field();
        print "What kind figure do you would like delete?\n";
        fscanf(STDIN, "%1s%d\n", $row, $str);
        if ($row && $str ) {
            if ($this->field[$str][$this->arr_row[$row]] != 'e') {
                $this->field[$str][$this->arr_row[$row]] = 'e';
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
                print "Choose type of figure:\n p - pawn\n r - rook\n h - horse\n b - bishop\n q - queen\n k - king\n e - exit\n";
                fscanf(STDIN, "%s", $figure);
                if ($figure == 'p' || $figure == 'r' || $figure == 'h' || $figure == 'b' || $figure == 'q' || $figure == 'k'){
                    print "Choose place: ";
                    fscanf(STDIN, "%1s%d\n", $row, $str);
                    $chess->add_figure($figure, $row, $str);
                    $flagFigure = false;
                }
                elseif ($figure == 'e') {
                    $flagFigure = false;
                }
                else
                    print "incorrect input\n";
            }
            break;
        case 4:
            if ($chess->count != 0) {
                $chess->move_figure();
            }
            else print "Field are empty, you will must add figure\n";
            break;
        case 5:
            if ($chess->count != 0) {
                $chess->delete_figure($row, $str);
            }
            else print "Field are empty, you will must add figure\n";
            break;
        case 6:
            $chess->read_file();
            break;
        case 0:
            $command = false;
            break;
        default:
            print "Incorrect command\n";
    }
}
?>
