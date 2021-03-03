<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Browser</title>

    <link rel="shortcut icon" href="/img/chaiwensong.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/index.css" type="text/css">
    <script src="js/jquery-1.12.1.min.js"></script>
    <style type="text/css">
        button,input{
            background: #eb94d0;
            /* 创建渐变 */
            background-image: -webkit-linear-gradient(top, #eb94d0, #2079b0);
            background-image: -moz-linear-gradient(top, #eb94d0, #2079b0);
            background-image: -ms-linear-gradient(top, #eb94d0, #2079b0);
            background-image: -o-linear-gradient(top, #eb94d0, #2079b0);
            background-image: linear-gradient(to bottom, #eb94d0, #2079b0);
            /* 给按钮添加圆角 */
            -webkit-border-radius: 28px;
            -moz-border-radius: 28px;
            border-radius: 28px;
            text-shadow: 3px 2px 1px #9daef5;
            -webkit-box-shadow: 6px 5px 24px #666666;
            -moz-box-shadow: 6px 5px 24px #666666;
            box-shadow: 6px 5px 24px #666666;
            font-family: Arial;
            color: #fafafa;
            font-size: 27px;
            padding: 19px;
            text-decoration: none;
        }
        /* 悬停样式 */
        button:hover {
            background: #2079b0;
            background-image: -webkit-linear-gradient(top, #2079b0, #eb94d0);
            background-image: -moz-linear-gradient(top, #2079b0, #eb94d0);
            background-image: -ms-linear-gradient(top, #2079b0, #eb94d0);
            background-image: -o-linear-gradient(top, #2079b0, #eb94d0);
            background-image: linear-gradient(to bottom, #2079b0, #eb94d0);
            text-decoration: none;
        }
    </style>
</head>


<body>

<form action="upload_file.php" method="post" enctype="multipart/form-data">
    <div align="center" style="padding-top: 40px">
        <input type="file" name="file" id="file">
        <input type="submit" name="submit" value="提交">
    </div>
</form>


<div id="wrap"></div>
<script>
    var $wrap = $("#wrap"),sw =$(window).width()/2,sh = $(window).height()/2,num=20;
    var $showBox,index = 0;
    (function () {
        // 向wrap中添加40个div,并向每个div中添加一个img
        for(var i=0;i<num;i++){
            $wrap.append( $("<div></div>"));
            $("<img>").attr({
                "src":"img/"+(i+1)+".jpg",
                "width":"100px",
                "height":"100px"
            }).appendTo( $("#wrap").find("div").eq(i));
        }
        $("img").ready(function () {
            //此时的wrap需要重新获取
            var wrapH = $("#wrap").height();
            var screenH = $(document).height();
            //调整wrap的居中显示
            if ( screenH > wrapH){
                $wrap.css({
                    "marginTop":(screenH - wrapH )/2+"px"
                })
            }

            $wrap.find("div").click(function () {
                index = $(this).index();
                showBox(index);
            });

            function showBox(index) {
                $(".showBox").remove();
                $("<div></div>").attr({
                    "class":"showBox"
                }).appendTo($("body"));
                $showBox = $(".showBox");
                var src = $wrap.find("div").eq(index).find("img").attr("src");
                $("<img>").attr({
                    "src":src
                }).appendTo($showBox);
                $("<span class='x'>x</span><span class='l'>&lt;</span><span class='r'>&gt;</span>").appendTo( $showBox );
                $showBox.find("img").eq(0).ready(function () {
                    $showBox.fadeIn(300);
                    var picW = $showBox.find("img").eq(0).width();
                    var picH = $showBox.find("img").eq(0).height();
                    //定义4个变量，表示showBox的宽高和margin值
                    var width,height,marginLeft,marginTop;

                    if (picW > picH){
                        //等比缩放 1、确定设定宽高sw sh;2.确定缩放比例 = 实际宽高/设定宽高； 3.确定缩放宽高 = 实际宽高/缩放宽高。
                        //height=实际高度/(实际宽度/设定宽度)
                        width = sw;
                        height = picH / (picW / sw);
                        marginLeft = - ( width/2 +18 );
                        marginTop = - ( height/2+18 );
                    }else {
                        height= sh;
                        width = picW /(picH /sh);
                        marginLeft = - ( width/2 +18 );
                        marginTop = - ( height/2+18 );
                    }
                    $showBox.find("img").eq(0).css({
                        "width":width +"px",
                        "height":height+"px"
                    }).parent(".showBox").css({
                        "marginLeft":marginLeft+"px",
                        "marginTop":marginTop+"px"
                    })
                });
                $showBox.find('.x').click(function(){
                    $showBox.fadeOut(300,function(){
                        $showBox.remove();
                    });
                });
                $showBox.find('.l').click(function(){
                    index--;
                    index=(index)<0?(num-1):index;
                    showBox(index);
                });
                $showBox.find('.r').click(function(){
                    index++;
                    index%=num;
                    showBox(index);
                })
            }
        })
    })();

    document.getElementById('file').addEventListener('change', function(e) {
        var files = e.target.files[0];
        if (files && files.size) {
            var size = files.size / 1000 / 1024;
            // 如果图片大于10M则重新上传，这里e.target.files[0].size单位是b
            if (size > 10) {
                alert('图片太大,请重新上传');
            }
            // 如果上传的不是图片格式，提示请选择图片
            var rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/svg\+xml|image\/tiff|image\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image\/x\-rgb|image\/x\-xbitmap|image\/x\-xpixmap|image\/x\-xwindowdump)$/i;
            if (!rFilter.test(files.type)) {
                alert('请选择图片格式!');
            }
            var reader = new FileReader();
            reader.readAsDataURL(files);
            reader.onloadstart = function () {
                console.log('图片正在上传处理......');
            };
            //操作完成
            reader.onload = function(e) {
                // file 对象的属性
                // elem.setAttribute('src', reader.result);
                $wrap.append( $("<div></div>"));
                $("<img>").attr({
                    "src":reader.result,
                    "width":"100px",
                    "height":"100px"
                }).appendTo( $("#wrap").find("div").eq(num++));
                $("img").ready(function () {
                    //此时的wrap需要重新获取
                    var wrapH = $("#wrap").height();
                    var screenH = $(document).height();
                    //调整wrap的居中显示
                    if ( screenH > wrapH){
                        $wrap.css({
                            "marginTop":(screenH - wrapH )/2+"px"
                        })
                    }

                    $wrap.find("div").click(function () {
                        index = $(this).index();
                        showBox(index);
                    });

                    function showBox(index) {
                        $(".showBox").remove();
                        $("<div></div>").attr({
                            "class":"showBox"
                        }).appendTo($("body"));
                        $showBox = $(".showBox");
                        var src = $wrap.find("div").eq(index).find("img").attr("src");
                        $("<img>").attr({
                            "src":src
                        }).appendTo($showBox);
                        $("<span class='x'>x</span><span class='l'>&lt;</span><span class='r'>&gt;</span>").appendTo( $showBox );
                        $showBox.find("img").eq(0).ready(function () {
                            $showBox.fadeIn(300);
                            var picW = $showBox.find("img").eq(0).width();
                            var picH = $showBox.find("img").eq(0).height();
                            //定义4个变量，表示showBox的宽高和margin值
                            var width,height,marginLeft,marginTop;

                            if (picW > picH){
                                //等比缩放 1、确定设定宽高sw sh;2.确定缩放比例 = 实际宽高/设定宽高； 3.确定缩放宽高 = 实际宽高/缩放宽高。
                                //height=实际高度/(实际宽度/设定宽度)
                                width = sw;
                                height = picH / (picW / sw);
                                marginLeft = - ( width/2 +18 );
                                marginTop = - ( height/2+18 );
                            }else {
                                height= sh;
                                width = picW /(picH /sh);
                                marginLeft = - ( width/2 +18 );
                                marginTop = - ( height/2+18 );
                            }
                            $showBox.find("img").eq(0).css({
                                "width":width +"px",
                                "height":height+"px"
                            }).parent(".showBox").css({
                                "marginLeft":marginLeft+"px",
                                "marginTop":marginTop+"px"
                            })
                        });
                        $showBox.find('.x').click(function(){
                            $showBox.fadeOut(300,function(){
                                $showBox.remove();
                            });
                        });
                        $showBox.find('.l').click(function(){
                            index--;
                            index=(index)<0?(num-1):index;
                            showBox(index);
                        });
                        $showBox.find('.r').click(function(){
                            index++;
                            index%=num;
                            showBox(index);
                        })
                    }
                })
            };
        }
    });

</script>

</body>
</html>
