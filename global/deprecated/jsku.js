    function ge(element){
        //return document.getElementById(element);
        //deprecated
        alert("remove me, im deprecated")
    }

    function gi(element){
        alert("ganti ke ku");
        return document.getElementById(element);
    }

    function qs(query){
        alert("ganti ke ku");
        return document.querySelector(query);
    }

    function cl(kata){
        alert("ganti ke ku");
        console.log(kata);
    }


    document.addEventListener("DOMContentLoaded",()=>{
        let epopup=document.getElementsByClassName("popup")[0];

        /* onklik outside
        epopup.addEventListener("click",(e)=>{
            //cl(e.target);
            if(e.target===e.currentTarget){
                epopup.style.display="none";
            }
        });
        */
        window.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            epopup.style.display="none";
        }
        })
    })
