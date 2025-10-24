/*
by me
info : framework js ku
start : 14 oktober 2025
last :
*/ 

    function ge(element){
        //return document.getElementById(element);
        //deprecated
        alert("remove me, im deprecated")
    }

    function gi(element){
        return document.getElementById(element);
    }

    function qs(query){
        return document.querySelector(query);
    }

    function cl(kata){
        console.log(kata);
    }


    // alert
      function xalert(kata){
      let isi=`
        <div class=alert>
          <div class="box">
          <div class="judul">
            <div class="close" onclick="this.parentElement.parentElement.parentElement.remove(0)">x</div>

          </div>
          <div class="isi">
            `+kata+`
          </div>
          </div>
        </div>`;

        document.body.insertAdjacentHTML("afterend",isi);
    }

  // confirm
      function closexconfirm(){
        document.getElementById("confirm").remove();
    }

    function xconfirm(kata,fclick){
                let isi=`<div id=confirm class="confirm">
            <div class="box">
                <div class="judul">
                    <div class="close" onclick="closexconfirm();">x</div>
                </div>
                <div class="isi">`+kata+`</div>
                <div class="footer">
                    <input class=ya type="button" value="ya">
                    <input class=tidak type="button" value="tidak">
                </div>
            </div>
        </div>

        `;
        document.body.insertAdjacentHTML("afterend",isi);
        let tidak=document.querySelector(".confirm .tidak");
        let ya=document.querySelector(".confirm .ya");

        tidak.addEventListener("click",closexconfirm);
        ya.addEventListener("click",fclick);
        ya.addEventListener("click",closexconfirm);
    }


// tabs


// <div class="tab">
//   <div class="judul">
//     <div class="item">ini judul 1</div>
//     <div class="item">ini judul 2</div>
//     <div class="item ">ini judul 3</div>
//   </div>
//   <div class="isi">
//     <div class="item">ini isinya loh</div>
//     <div class="item">ini salah satu kedua</div>
//     <div class="item">yang ketiga</div>
//   </div>
// </div>

  function getChildNodeIndex(elem) {
    let position = 0;
    while ((elem = elem.previousSibling) != null) {
      if (elem.nodeType != Node.TEXT_NODE)
        position++;
    }

    return position;
  }

  function kliktab(){
    let index=getChildNodeIndex(this);
    settabaktif(index);
  }

  function settabaktif(index){
    let juduls=document.querySelectorAll(".tab .judul .item");
    let isis=document.querySelectorAll(".tab .isi .item");
    //set judul    
    for (let i = 0; i < juduls.length; i++) {
       juduls[i].classList.remove("aktif");
    }
    juduls[index].classList.add("aktif");
    //set isi
    for (let i = 0; i < isis.length; i++) {
       isis[i].style.display="none";
    }
    isis[index].style.display="block";
  
  }

  document.addEventListener("DOMContentLoaded",()=>{
    settabaktif(0);
    let juduls=document.querySelectorAll(".tab .judul .item");
    for (let i = 0; i < juduls.length; i++) {
       juduls[i].addEventListener("click",kliktab);
    }
})
