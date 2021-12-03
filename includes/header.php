 <body>
 <header>
   <div class="container">
     <div class="row">
       <div class="col-3">
         <a href="calendar.php">
           <img src="assets/image/icons8-calendar-160 1.png" class="header_calendar" width="120px" height="120px" />
         </a>
       </div>
       <div class="col-6">
         <div class="header_day-today">
           <a class="text-decoration-none" href="<?php echo $root . '/index.php'; ?>">
             <h1 id="h1">
               <?php
                $arr = [
                  'января',
                  'февраля',
                  'марта',
                  'апреля',
                  'мая',
                  'июня',
                  'июля',
                  'августа',
                  'сентября',
                  'октября',
                  'ноября',
                  'декабря'
                ];

//                 $month = date('n');
//                 $day = date("j");
                $month = 9;
                $day = 20;

                $today = $day . " " . $arr[$month - 1];
                echo $today;
                
              
                ?>
                <script>
                 
                  console.log(date)
                </script>
             </h1>
           </a>
         </div>
       </div>
       <div class="col-3">
         <div class="control-panel">
           <img class="control-panel_item search" src="assets/image/Кнопка поиска.png" />
           <div class="control-panel_item burger">
             <div class="burger-line"></div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </header>
