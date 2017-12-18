<style>
      #blok {
        position:relative;
        width:100%;
        height:2.2em;
        line-height:2.2em;
        margin:0 auto;
        border:3px solid #DDD;
        font-size:1.5em;
        background-color:#EEE;
        overflow:hidden;
      }

      #blok > p {
        position:absolute;
        margin:0;
        white-space:nowrap;
        animation:przesun 30s linear infinite;
        -webkit-animation:przesun 30s linear infinite; /* dla Google Chrome, Safari, Opera 15+ */
      }

      /* ------------ANIMACJA-PRZESUN------------ */
      @keyframes przesun
      {
        0% { transform:translateX(0); padding-left:110%; }
        100% { transform:translateX(-100%); padding-left:110%; }
      }

      @-webkit-keyframes przesun /* dla Google Chrome, Safari, Opera 15+ */
      {
        0% { -webkit-transform:translateX(0); padding-left:110%; }
        100% { -webkit-transform:translateX(-100%); padding-left:110%; }
      }
    </style>
<div id="blok">
    <p>Ten tekst w tym elemencie - p - będzie poruszał się, gdy dodamy do niego odpowiednią animację oraz właściwości CSS.</p>
</div>
