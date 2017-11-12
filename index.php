<?php
//Imagick::readImage
//;extension=php_mbstring.dll


//0 standard horizontal text, 
//1 grayscale standard horizontal text
//2 mono standard horizontal text 
//3,4 profile free transform text 
$type = $_REQUEST['type'];
$scale = $_REQUEST['scale'];
$demotype = $_REQUEST['demotype'];
if(!$demotype){
	$demotype = 0;
}

$text = $_REQUEST['text'];
if(!$text){
$text = '中文维基百科使用汉字书写，汉字是汉族或华人的共同文字，是中国大陆、台湾、新加坡、马来西亚、香港、澳门的唯一官方文字或官方文字之一。'
.'中文维基百科虽未规定以现代标准汉语的语法书写，但维基人因共识及默契而以其为共通形式，有时也适度掺入方言或文言文。 '
.'中文维基百科的编辑者来自于世界各地的华文用户，根据2006年9月初维基百科对各语版本的编辑者来源的统计结果，香港地区的编辑者占28.6%、台湾地区占25.9%，而美国和荷兰则分别占13.7%及8.2%。'
.'近年来，中国大陆地区的维基百科编辑者正在迅速增加；而2011年10月至2012年9月间编辑数量的统计，台湾占36.9%、香港26.3%、中国大陆20.9%，美国、加拿大及澳洲分别占5.6%、1.7%及1.6%。'
.'中文维基百科由来自各地、拥有不同背景、不同的政治立场与社会观念及国家的华文用户共同参与，其文章内容是全球华文用户相互协调折衷后的结果。'
.'中文版维基百科成为来自中国大陆、台湾和香港的编辑们的战场，上演着激烈的政治、文化和意识形态的交锋，呈现不同版本的中国。'
.'此外，包括中文维基百科在内，各语言版本对于非自由版权下的内容，或者版权、来源不明的文章和图片，保有不同于当地网络氛围的，极为严格的审查制度。'
.'原则上任何不能用于自由传播或者禁止无经济补偿地应用于商业领域的文章、图片，均不会被保留在中文维基百科。'
.'中文维基百科广泛且深入地介绍中华文化、中国历史与中国大陆、香港、澳门、新加坡、马来西亚、台湾等地的事物。';
}

$textPattern = $text;
//TODO 中英文混排？
//$textPattern = SBC_DBC($textPattern,0);
//$textPattern = filterSymbols($textPattern);
if(mb_strlen($textPattern,'utf-8')== 0){
	$textPattern = $text;
}

$uniqueId = generateRandomString();

if($demotype == 1){
$allowedExts = array("gif", "jpeg", "jpg", "png");
$extension = end(explode(".", $_FILES["file"]["name"]));
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/png"))
//&& ($_FILES["file"]["size"] < 20000)  //less than 20kb
&& in_array($extension, $allowedExts))
{
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Upload Image Error: " . $_FILES["file"]["error"] . "<br/>";
    }
  else
    {
    //echo "Upload: " . $_FILES["file"]["name"] . "<br/>";
    //echo "Type: " . $_FILES["file"]["type"] . "<br/>";
    //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br/>";
    //echo "Stored in: " . $_FILES["file"]["tmp_name"];
	
	if($_FILES["file"]["type"] == "image/gif"){
		$filepostfix = ".gif";
	}else if($_FILES["file"]["type"] == "image/jpeg"){
		$filepostfix = ".jpeg";
	}else if($_FILES["file"]["type"] == "image/jpg"){
		$filepostfix = ".jpg";
	}else if($_FILES["file"]["type"] == "image/png"){
		$filepostfix = ".png";
	}
	$imageUploaded = "images/upload/".$uniqueId.$filepostfix;
	
	if (file_exists($imageUploaded ))
    {
		echo $_FILES["file"]["name"] . " already exists. ";
    }
    else
    {
		move_uploaded_file($_FILES["file"]["tmp_name"], $imageUploaded );
		//echo "Stored in: " . "images/upload/" . $imageUploaded ;
    }
    }
  }
else
  {
  echo '<div class="ui-widget">
	<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
		<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<strong>Alert:</strong> You have uploaded Invalid file.</p>
	</div>
</div>';
  }
  
}


if(!$type){
	$type = 0;
}
if(!$scale)$scale = 30; //1-100
$scaletext = ' 比例： %'.$scale;

$FONT_SIZE = 1;
?>
<!DOCTYPE html>
<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<head>
<script src="js/js/jquery-1.9.1.js"></script>
<script src="js/js/jquery-ui-1.10.2.custom.min.js"></script>
<link rel="stylesheet" href="js/css/south-street/jquery-ui-1.10.2.custom.min.css">
<style>
font {font-size:xx-small;}
div#outtext{font-size:<?php echo $FONT_SIZE;?>px;line-height:<?php echo $FONT_SIZE;?>px; }
div#outtext a{ float:left; width:<?php echo $FONT_SIZE;?>px; height:<?php echo $FONT_SIZE;?>px; text-align:center;}
</style>

<script>
  $(function() {
    $( "#tabs" ).tabs({ active: <?php echo $demotype;?> });
	
	$( "input[type=submit], button" ).button();
	$('#controlfontsizeminus').click(function(){
		var v = $( "#slider-fontsize" ).val();
		v--;
		if(v<=1){v= 1;}
		$( "#slider-fontsize" ).val(v);
		$( "#slider-fontsize" ).slider('value', v);
		//$( "#slider-fontsize" ).slider('refresh');
		change_font_size(v);
	});
	$('#controlfontsizeplus').click(function(){
		var v = $( "#slider-fontsize" ).val();
		v++;
		if(v>=10){v= 10;}
		$( "#slider-fontsize" ).val(v);
		$( "#slider-fontsize" ).slider('value', v);
		//$( "#slider-fontsize" ).slider('refresh');
		change_font_size(v);
	});
	
	$( "#slider-scale0" ).slider({
      range: "min",
      value: <?php echo $scale;?>,
      min: 20,
      max: 60,
      slide: function( event, ui ) {
        $( "#scale0" ).val( ui.value );
      }
    });
	$( "#slider-scale1" ).slider({
      range: "min",
      value: <?php echo $scale;?>,
      min: 20,
      max: 60,
      slide: function( event, ui ) {
        $( "#scale1" ).val( ui.value );
      }
    });
	
	$( "#slider-fontsize" ).slider({
      range: "min",
      value: <?php echo $FONT_SIZE;?>,
      min: 1,
      max: 12,
      slide: function( event, ui ) {
		var fs = ui.value;
        change_font_size(fs);
      }
    });
	
	function change_font_size(fs){
		$( "#controlfontsize" ).val( fs );
		$( "#outtext" ).css("font-size", fs+"px");
		$( "#outtext" ).css("line-height", fs+"px");
		$( "#outtext a" ).css("width", fs+"px");
		$( "#outtext a" ).css("height", fs+"px");
	}
	
	$( "#tabs" ).show();
  });
  
  </script>
  
</head>

<body>

<div id="tabs" style="display:none">
  <ul>
    <li><a href="#tabs-1">Select sample photo</a></li>
    <li><a href="#tabs-2">Upload your photo</a></li>
  </ul>
  <div id="tabs-1">
    	<form name="form2" action="index.php" method="post" enctype="multipart/form-data">
			<select name="type" >
			<option value="0" <?php echo $type==0?"selected":""?>>Standard horizontal demo</option>
			<option value="1" <?php echo $type==1?"selected":""?>>Standard Gray scale horizontal demo</option>
			<option value="2" <?php echo $type==2?"selected":""?>>Standard Mono horizontal demo</option>
			<option value="3" <?php echo $type==3?"selected":""?>>Profile Gray scale horizontal demo 1</option>
			<option value="4" <?php echo $type==4?"selected":""?>>Profile Gray scale horizontal demo 2</option>
			<!--<option value="5" <?php echo $type==5?"selected":""?>>Profile Gray scale free transform demo(TODO)</option>-->
			<!--
			<option value="0" <?php echo $type==0?"selected":""?>>标准水平展示</option>
			<option value="1" <?php echo $type==1?"selected":""?>>灰度水平展示</option>
			<option value="2" <?php echo $type==2?"selected":""?>>单色水平展示</option>
			<option value="3" <?php echo $type==3?"selected":""?>>轮廓灰度水平展示 1</option>
			<option value="4" <?php echo $type==4?"selected":""?>>轮廓灰度水平展示 2</option>
			<option value="5" <?php echo $type==5?"selected":""?>>轮廓灰度自由字体（未完成）</option>
			-->
			</select>
			<p>
			  <label for="scale">Scale %:</label>
			  <input type="text" id="scale0" name="scale" style="border: 0; color: #f6931f; font-weight: bold;" value="<?php echo $scale;?>"/>
			</p>
			<div id="slider-scale0" style="width:50%"></div>
			<br/>
			<textarea name="text" rows="4" cols="50"/><?php echo $text;?></textarea>
			<br/>
			<input type="hidden" value="0" name="demotype"/>
			<input type="submit" value="Submit" />
		</form>
  </div>
  <div id="tabs-2">
	<form name="form1" action="index.php" method="post" enctype="multipart/form-data">
		<label for="file">Upload your photo:</label>
		<input type="file" name="file" id="file"><br/>

		<p>
			  <label for="scale">Scale %:</label>
			  <input type="text" id="scale1" name="scale" style="border: 0; color: #f6931f; font-weight: bold;" value="<?php echo $scale;?>"/>
		</p>
		<div id="slider-scale1" style="width:100%"></div>
		<br/>
		<textarea name="text" rows="4" cols="50"/><?php echo $text;?></textarea>
		<br/>
		<input type="hidden" value="1" name="demotype"/>
		<input type="submit" value="Submit" />
	</form>
  </div>
  
</div>



<?php



$flag = 1;
$IMG_TMPL = './images/templ/';

if($demotype == 0){
	if($flag == 0){
		$imgFile = $IMG_TMPL."g.png";
	}else if($flag == 1){
		$imgFile = $IMG_TMPL."wewons.jpg";
	}
	if($type == 2 ){
		$imgFile = $IMG_TMPL."profile.jpeg";
	}
	else if($type == 3){
		$imgFile = $IMG_TMPL."profile.jpeg";
	}
	else if($type == 4){
		$imgFile = $IMG_TMPL."profile1.jpeg";
	}
	else if($type == 5){
		$imgFile = $IMG_TMPL."profile1.jpeg";
	}
}else{
	$imgFile = $imageUploaded;
}
if(!$imgFile){
	return;
}

$filepostfix = strtolower(substr($imgFile,-3));
if( $filepostfix == 'jpg' || $filepostfix  == 'peg' ){
	$im = imagecreatefromjpeg($imgFile);
}
else if( $filepostfix == 'png' ){
	$im = imagecreatefrompng($imgFile);
}


$imgWidth = imagesx($im);
$im = scale($im, $scale);
$imgWidth = imagesx($im);
$imgHeight = imagesy($im);

$size = floatval($imgWidth * $imgHeight);
$textLength = floatval(mb_strlen($textPattern,'utf-8'));
$text = '';
if($size < $textLength){
	$text = $textPattern;
}else{
	$leftTextLength = $size % $textLength;
	$count = floor ($size/$textLength);
	for($i=0,$j=$count;$i<$j;$i++){
		$text .= $textPattern;
	}
	if($leftTextLength){
		$text .= mb_substr($textPattern, 0, $leftTextLength, 'utf-8'); 
	}
}
//echo $count.'/'.$textLength.'/'.$leftTextLength.'/'.$imgWidth.'/'.$imgHeight.'/'.mb_strlen($text,'utf-8');
//return;
$colorArray = array();
$out = '';

echo '<div class="ui-tabs-panel ui-widget-content ui-corner-all" style="padding:0 20px 20px 20px;">
	<table>
	<tr>
	<td>
		<p>
			<label for="controlfontsize">Control Font Size:</label>
			<button id="controlfontsizeminus" >-</button>
			<input type="text" id="controlfontsize" name="controlfontsize" style="border: 0; color: #f6931f; font-weight: bold;" value="'.$FONT_SIZE.'"/>
			<button id="controlfontsizeplus" >+</button>
		</p>
		<div id="slider-fontsize" style="width:50%"></div>
	</td>
	<td>
	<label>Original image:</label>
	<img src="'.$imgFile.'" style="width:'.($imgWidth*50/100).'px;height:'.($imgHeight*50/100).'px" />
	</td>
	</tr>
	</table>
	</div>';
	
echo '<div id="outtext">';
//0 standard horizontal text
if($type  == 0){
	//echo "--------------------标准经典排版".$scaletext."-----------------------------------</br>\n";
	for($y=0;$y<$imgHeight;$y++){
		for($x=0;$x<$imgWidth;$x++){
			$ndx = imagecolorat($im,$x,$y);
			$aryColors = imagecolorsforindex($im,$ndx);
			
			$rhex = substr("0".dechex($aryColors['red']),-2);
			$ghex = substr("0".dechex($aryColors['green']),-2);
			$bhex = substr("0".dechex($aryColors['blue']),-2);
			
			echo '<a style="color:#'.$rhex.$ghex .$bhex.'">'.mb_substr($text, $imgWidth*$y+$x, 1, 'utf-8').'</a>';
		}
		echo "</br>\n";
	}

}
//1 grayscale standard horizontal text
else if($type  == 1){
	//echo "--------------------灰度经典排版".$scaletext."-----------------------------------</br>\n";
	imagegreyscale($im);
	
	for($y=0;$y<$imgHeight;$y++){
		for($x=0;$x<$imgWidth;$x++){
			$ndx = imagecolorat($im,$x,$y);
			$aryColors = imagecolorsforindex($im,$ndx);
			
			$rhex = substr("0".dechex($aryColors['red']),-2);
			$ghex = substr("0".dechex($aryColors['green']),-2);
			$bhex = substr("0".dechex($aryColors['blue']),-2);
			
			echo '<a style="color:#'.$rhex.$ghex .$bhex.'">'.mb_substr($text, $imgWidth*$y+$x, 1, 'utf-8').'</a>';
		}
		echo "</br>\n";
	}
}
//2 mono standard horizontal text , use to get the profile!!!
else if($type  == 2){
	//echo "--------------------二值经典排版".$scaletext."-----------------------------------</br>\n";
	imagegreyscale_test($im, 180, true);
	
	for($y=0;$y<$imgHeight;$y++){
		for($x=0;$x<$imgWidth;$x++){
			$ndx = imagecolorat($im,$x,$y);
			$aryColors = imagecolorsforindex($im,$ndx);
			
			$rhex = substr("0".dechex($aryColors['red']),-2);
			$ghex = substr("0".dechex($aryColors['green']),-2);
			$bhex = substr("0".dechex($aryColors['blue']),-2);
			
			echo '<a style="color:#'.$rhex.$ghex .$bhex.'">'.mb_substr($text, $imgWidth*$y+$x, 1, 'utf-8').'</a>';
		}
		echo "</br>\n";
	}
}
//3 profile free transform text 
else if($type  == 3 || $type  == 4){
	//echo "--------------------轮廓自由排版".$scaletext."-----------------------------------</br>\n";
	if($type  == 3 ){
		$level = 180; 
	}else if($type  == 4){
		$level = 250; 
	}
	
	//imagefilter($im, IMG_FILTER_GRAYSCALE);
	
	for($y=0;$y<$imgHeight;$y++){
		for($x=0;$x<$imgWidth;$x++){
			$ndx = imagecolorat($im,$x,$y);
			$aryColors = imagecolorsforindex($im,$ndx);
			
			$r = floatval($aryColors['red']);
			$g = floatval($aryColors['green']);
			$b = floatval($aryColors['blue']);
			$gray = ($r*30 + $g*59 + $b*11 + 50) / 100;
			
			$use = false;
			if($gray <= $level){
				$use = $negate ?false:true;
			}else{
				$use = $negate ?true:false;
			}
				
			$rhex = substr("0".dechex($aryColors['red']),-2);
			$ghex = substr("0".dechex($aryColors['green']),-2);
			$bhex = substr("0".dechex($aryColors['blue']),-2);
			
			if($use){
				echo '<a style="color:#'.$rhex.$ghex .$bhex.'">'.mb_substr($text, $imgWidth*$y+$x, 1, 'utf-8').'</a>';
			}else{
				echo '<a style="color:#ffffff">'.'口'.'</a>';
			}
		}
		echo "</br>\n";
	}
}

echo "</div>";


	//http://www.white-hat-web-design.co.uk/blog/resizing-images-with-php/
	function scale($im, $scale) {
		$w = imagesx($im);
		$h = imagesy($im);
		$nw = $w * $scale/100;
		$nh = $h * $scale/100;
		$nim = imagecreatetruecolor($nw, $nh);
		imagecopyresampled($nim, $im, 0, 0, 0, 0, $nw, $nh, $w, $h);
		return $nim;
	}
	//filter all chinese symbol
	function filterSymbols($str){
		preg_match_all('/[\x{4e00}-\x{9fff}]+/u', $str, $matches);
		return join('', $matches[0]);
	}
	
	//http://www.nowamagic.net/php/php_InterceptionString.php


	//slightly poor quality, but very fast...
	function imagegreyscale(&$img, $dither=1) {   
		if (!($t = imagecolorstotal($img))) {
			$t = 256;
			imagetruecolortopalette($img, $dither, $t);   
		}
		for ($c = 0; $c < $t; $c++) {   
			$col = imagecolorsforindex($img, $c);
			$min = min($col['red'],$col['green'],$col['blue']);
			$max = max($col['red'],$col['green'],$col['blue']);
			$i = ($max+$min)/2;
			imagecolorset($img, $c, $i, $i, $i);
		}
	}
	
	function imagegreyscale_test(&$im, $level = 200, $negate = false) {  
		$w = imagesx($im);
		$h = imagesy($im);	
		for($y=0;$y<$h;$y++){
			for($x=0;$x<$w;$x++){
				$ndx = imagecolorat($im,$x,$y);
				$aryColors = imagecolorsforindex($im, $ndx);
				
				$r = floatval($aryColors['red']);
				$g = floatval($aryColors['green']);
				$b = floatval($aryColors['blue']);
				
				//http://bbs.ednchina.com/BLOG_ARTICLE_1999487.HTM
				$gray = ($r*30 + $g*59 + $b*11 + 50) / 100;
				if($gray <= $level){
					$aryColors[0] = $aryColors[1] = $aryColors[2] = $negate ?0:255;
				}else{
					$aryColors[0] = $aryColors[1] = $aryColors[2] = $negate ?255:0;
				}
				
				imagesetpixel($im, $x, $y, imagecolorallocate($im, $aryColors[0], $aryColors[1], $aryColors[2]));
			}
		}
		
			/*if (($im[$y][$x+1] - $im[$y][$x])*($im[$y][$x+1] - $im[$y][$x]) +
			   ($im[$y+1][$x] - $im[$y][$x])*($im[$y+1][$x] - $im[$y][$x]) > 400)
			  fout << 0 << " ";
			else
			fout << 255 << " ";*/
	}
	
	function imagecheckcolor($im, $x, $y){
	
	}
	
	function generateRandomString($length = 8) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	//半角和全角转换函数，第二个参数如果是0,则是半角到全角；如果是1，则是全角到半角
	function SBC_DBC($str,$args2=1) { 
		$DBC = Array(
		'０' , '１' , '２' , '３' , '４' , 
		'５' , '６' , '７' , '８' , '９' ,
		'Ａ' , 'Ｂ' , 'Ｃ' , 'Ｄ' , 'Ｅ' , 
		'Ｆ' , 'Ｇ' , 'Ｈ' , 'Ｉ' , 'Ｊ' ,
		'Ｋ' , 'Ｌ' , 'Ｍ' , 'Ｎ' , 'Ｏ' , 
		'Ｐ' , 'Ｑ' , 'Ｒ' , 'Ｓ' , 'Ｔ' ,
		'Ｕ' , 'Ｖ' , 'Ｗ' , 'Ｘ' , 'Ｙ' , 
		'Ｚ' , 'ａ' , 'ｂ' , 'ｃ' , 'ｄ' ,
		'ｅ' , 'ｆ' , 'ｇ' , 'ｈ' , 'ｉ' , 
		'ｊ' , 'ｋ' , 'ｌ' , 'ｍ' , 'ｎ' ,
		'ｏ' , 'ｐ' , 'ｑ' , 'ｒ' , 'ｓ' , 
		'ｔ' , 'ｕ' , 'ｖ' , 'ｗ' , 'ｘ' ,
		'ｙ' , 'ｚ' , '－' , '　'  , '：' ,
		'．' , '，' , '／' , '％' , '＃' ,
		'！' , '＠' , '＆' , '（' , '）' ,
		'＜' , '＞' , '＂' , '＇' , '？' ,
		'［' , '］' , '｛' , '｝' , '＼' ,
		'｜' , '＋' , '＝' , '＿' , '＾' ,
		'￥' , '￣' , '｀'
		);
		$SBC = Array( //半角
		'0', '1', '2', '3', '4', 
		'5', '6', '7', '8', '9',
		'A', 'B', 'C', 'D', 'E', 
		'F', 'G', 'H', 'I', 'J',
		'K', 'L', 'M', 'N', 'O', 
		'P', 'Q', 'R', 'S', 'T',
		'U', 'V', 'W', 'X', 'Y', 
		'Z', 'a', 'b', 'c', 'd',
		'e', 'f', 'g', 'h', 'i', 
		'j', 'k', 'l', 'm', 'n',
		'o', 'p', 'q', 'r', 's', 
		't', 'u', 'v', 'w', 'x',
		'y', 'z', '-', ' ', ':',
		'.', ',', '/', '%', '#',
		'!', '@', '&', '(', ')',
		'<', '>', '"', '\'','?',
		'[', ']', '{', '}', '\\',
		'|', '+', '=', '_', '^',
		'$', '~', '`'
		);
		if($args2==0)
		return str_replace($SBC,$DBC,$str);  //半角到全角
		if($args2==1)
		return str_replace($DBC,$SBC,$str);  //全角到半角
		else
		return false;
}


imagedestroy($im);
?>

</body>

</html>