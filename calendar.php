<?php
require_once("includes/head.php");
require_once("includes/header.php");


?>



<body>





  <div class="container cal">
    <div class="calendar">
      <div class="month">
        <i class="fas fa-angle-left prev"></i>
        <div class="date">
          <h1 id="stringMonth"></h1>
          
        </div>
        <i class="fas fa-angle-right next"></i>
      </div>
      <div class="weekdays">
        <div>Пн</div>
        <div>Вт</div>
        <div>Ср</div>
        <div>Чт</div>
        <div>Пт</div>
        <div>Сб</div>
        <div>Вс</div>
      </div>
      <div class="days"></div>
    </div>
  </div>


  
  <script>
    window.onload = function() {
      
      //экземпляр даты
      const date = new Date();
        let todayMonth;

      // ф-ция создания календаря
      const renderCalendar = () => {

        date.setDate(1);


        // все дни
        const monthDays = document.querySelector(".days");
        // узнаем какой последний день
        const lastDay = new Date(
          date.getFullYear(),
          date.getMonth() + 1,
          0
        ).getDate();


        const prevLastDay = new Date(
          date.getFullYear(),
          date.getMonth(),
          0
        ).getDate();

        const firstDayIndex = date.getDay() + 6;


        const lastDayIndex = new Date(
          date.getFullYear(),
          date.getMonth() + 1,
          0
        ).getDay();
        // дни след месяца серые
        const nextDays = 7 - lastDayIndex - 1;

        //массив месяцов
        const months = [
          "Январь",
          "Февраль",
          "Март",
          "Апрель",
          "Май",
          "Июнь",
          "Июль",
          "Август",
          "Сентябрь",
          "Октябрь",
          "Ноябрь",
          "Декабрь",
        ];
        // в хедере надпись сегоднешней даты 
        document.querySelector(".date h1").innerHTML = months[date.getMonth()];
          
        todayMonth = document.querySelector(".date h1").innerHTML = months[date.getMonth()];
        // document.querySelector(".date h1").innerHTML = months[<?php echo $month - 1 ?>];

        // document.querySelector(".date p").innerHTML = new Date().toDateString();

        let days = "";

        // создаем серые дни прошлого месяца
        for (let x = firstDayIndex; x > 0; x--) {
          days += `<div class="prev-date">${prevLastDay - x + 1}</div>`;

        }


        // создаем все дни и сегодняшний
        for (let i = 1; i <= lastDay; i++) {
          if (
            <?php echo $day; ?> == i
            
            // && date.getMonth() === new Date().getMonth()
          ) {
            days += `<div class="today day">${i}</div>`;
          } else {
            days += `<div class="day">${i}</div>`;

          }
        }


        // создаем серые дни следуещего месяца
        for (let j = 1; j <= nextDays; j++) {
          days += `<div class="next-date">${j}</div>`;
          monthDays.innerHTML = days;
        }
      };

      // кнопка назад 
      document.querySelector(".prev").addEventListener("click", () => {
       
        // идем на предыдущий месяц
        date.setMonth(date.getMonth() - 1);
        renderCalendar();
        
        // массив всех дней
        
        clickON()

        
      });
      // кнопка вперед 
      document.querySelector(".next").addEventListener("click", () => {
       
        // идем на следующий месяц
        date.setMonth(date.getMonth() + 1);
        renderCalendar();

        
        clickON()
         console.log(todayMonth)
          if(todayMonth == date.getMonth()){
              today.classList.remove("today")
          }
         
        

      });

      renderCalendar();
      

      

      function clickON() {
        
      let day = document.querySelectorAll(".day")
      let today = document.querySelector(".today")
        
          
          today.addEventListener("mouseover" , function(){
            today.style.border = "0.2rem solid #777"
            today.style.cursor = "pointer"            
          })
          today.addEventListener("mouseout" , function(){
            today.style.border = "none"
            today.style.cursor = "none"            
          })
         
      day.forEach.call(day, function(el) {
   

          el.addEventListener('click', function(e) {
            today.style.backgroundColor = "#222227"
            today.addEventListener("click" , function(){
              today.style.backgroundColor = "rgb(250, 152, 152)"
              
            })

          for (let i = 0; i < day.length; i++) {

            if (day[i].classList.contains("active")) {
              day[i].classList.remove("active")
            } else this.classList.add("active")
          }

          
          let dayReq = el.innerHTML
          let monthReq = date.getMonth() + 1

          const url = '/test.php?day=' + dayReq + "&month=" + monthReq;
          console.log(url)
              
            

          fetch(url)
            .then(function(response) {
              return response.text();
            })
            .then(function(body) {
              console.log(body);
              let res = JSON.parse(body)


              for(let i = 1; i <= res.length; i++){
                let Event = document.createElement("a")
                Event.href = 'event.php?id=' + res[i-1].id
                Event.className = "event-calendar";
                document.body.append(Event)
                

                let idEvent  = res[i-1].id
                let dateEvent  = res[i-1].date
                let shortTextEvent  = res[i-1].short_text

                let redBlock = document.createElement("div")
                redBlock.className = "date-event";
                redBlock.innerHTML = dateEvent;
                Event.appendChild(redBlock);
                let whiteBlock = document.createElement("div")
                whiteBlock.className = "description-event";
                whiteBlock.innerHTML = shortTextEvent;
                Event.appendChild(whiteBlock);
                
              // let dayClick = document.querySelectorAll(".days")
                let eventCalendar = document.querySelector(".event-calendar")
              // let body = document.querySelector("body")
              // dayClick.forEach((element) => {
              //   console.log(eventCalendar)
              //   element.addEventListener("click" , function(){
              //     body.removeChild(eventCalendar);
              // })
              // })
//              eventCalendar.addEventListener("click" , function(){
//                window.location.href='event.php?id=' + res[i].id;
//              })
              
            
              }
              
             
              
            });
           let div = document.querySelectorAll(".event-calendar")
           div.forEach.call(div, function(el) {
              el.remove()
           })
           function selectedDate(){
           let h1 = document.querySelector("#h1") 
           let stringMonth  = document.getElementById("stringMonth").innerHTML
           stringMonth = stringMonth.substring(0, stringMonth.length - 1)
           if(stringMonth == "Авгус" || stringMonth == "Мар" ){
            stringMonth += "та"
           }else stringMonth += "я"
           
          
           h1.innerHTML = el.innerHTML + " " + stringMonth
           }
           selectedDate()
          
        })
      })
        
          
      }
      clickON()
    }
  </script>

</body>

</html>