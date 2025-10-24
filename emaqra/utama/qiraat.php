

<style>
.qiraat {
    display: flex;
    flex-wrap: wrap;
    gap: 11px;
    justify-content: center;
}

.qiraat a {
    text-decoration: none;
    border: 2px solid red;
    font-size: 15px;
    place-content: center;
    color: hsl(0 0% 33% / 1);

}

.qiraat a:hover {
    background-color: pink;
    border-radius: 55px;
    /* border-width: 3px; */
}


</style>

<script>
    document.addEventListener("DOMContentLoaded",()=>{
    links=document.querySelectorAll(".qiraat a");
    for (let i = 0; i < links.length; i++) {
        const x=links[i];
        x.classList.add("card");
        x.classList.add("grid");
        x.classList.add("padding");
        x.classList.add("center");
        // x.classList.add("bold");
        x.classList.add("lowercase");
        x.classList.add("borderradius");
        x.classList.add("transition");
    }
  })

</script>


<div class="centermargin vmaxwidth">
    <h1>qiraat</h1>
    <div class="qiraat">
        <a href="qiraat.php?id=0" target="_blank">MUSHAF IMAM NAFI'</a>
        <a href="qiraat.php?id=1" target="_blank">MUSHAF IMAM IBNU KATSIR</a>
        <a href="qiraat.php?id=2" target="_blank">MUSHAF IMAM ABU 'AMR</a>
        <a href="qiraat.php?id=3" target="_blank">MUSHAF IMAM IBNU 'AMIR</a>
        <a href="qiraat.php?id=4" target="_blank">MUSHAF IMAM 'ASHIM</a>
        <a href="qiraat.php?id=5" target="_blank">MUSHAF IMAM HAMZAH</a>
        <a href="qiraat.php?id=6" target="_blank">MUSHAF IMAM ALI AL-KISAI</a>
        <a href="qiraat.php?id=7" target="_blank">MUSHAF IMAM ABU JA'FAR</a>
        <a href="qiraat.php?id=8" target="_blank">MUSHAF IMAM YA'CUB</a>
        <a href="qiraat.php?id=9" target="_blank">MUSHAF IMAM KHOLAF</a>
        <a href="qiraat.php?id=10" target="_blank">KAIDAH QIRAAT TUJUH</a>
    </div>

</div>
