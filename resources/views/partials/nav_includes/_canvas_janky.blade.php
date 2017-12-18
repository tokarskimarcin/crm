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
        animation:przesun 15s linear infinite;
        -webkit-animation:przesun 15s linear infinite; /* dla Google Chrome, Safari, Opera 15+ */
      }

      /* ------------ANIMACJA-PRZESUN------------ */
      @keyframes przesun
      {
        0% { transform:translateX(0); padding-left:100%; }
        50% { transform:translateX(-100%); padding-left:100%; }
      }

      @-webkit-keyframes przesun /* dla Google Chrome, Safari, Opera 15+ */
      {
        0% { -webkit-transform:translateX(0); padding-left:100%; }
        50% { -webkit-transform:translateX(-100%); padding-left:100%; }
      }
    </style>
<div id="blok">
    <p>Liczba nieodsłuchanych janków: 3 </p>
</div>
