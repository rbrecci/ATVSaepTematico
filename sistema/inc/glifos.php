<?php // Sprite SVG com os glifos mágicos e o filtro de borda rasgada. Desenhados do zero para este projeto. ?>
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
  <defs>
    <filter id="rasgado" x="-5%" y="-5%" width="110%" height="110%">
      <feTurbulence type="fractalNoise" baseFrequency="0.03" numOctaves="4" seed="7" result="ruido"/>
      <feDisplacementMap in="SourceGraphic" in2="ruido" scale="16" xChannelSelector="R" yChannelSelector="G"/>
    </filter>

    <symbol id="g-circulo" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="50" cy="50" r="46"/>
        <circle cx="50" cy="50" r="40" stroke-dasharray="2 5"/>
        <path d="M50 12 L83 69 H17 Z"/>
        <path d="M50 88 L17 31 H83 Z"/>
        <circle cx="50" cy="50" r="9"/>
        <circle cx="50" cy="50" r="2" fill="currentColor"/>
      </g>
    </symbol>

    <symbol id="g-olho" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <path d="M6 54 Q50 16 94 54 Q50 92 6 54 Z"/>
        <circle cx="50" cy="54" r="14"/>
        <circle cx="50" cy="54" r="5" fill="currentColor"/>
        <path d="M50 8 V20 M22 18 L28 28 M78 18 L72 28"/>
      </g>
    </symbol>

    <symbol id="g-lua" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <path d="M70 14 A38 38 0 1 0 70 86 A44 44 0 0 1 70 14 Z"/>
        <circle cx="76" cy="50" r="3" fill="currentColor"/>
        <path d="M84 30 V38 M80 34 H88"/>
      </g>
    </symbol>

    <symbol id="g-estrela" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <path d="M50 6 V94 M6 50 H94 M19 19 L81 81 M81 19 L19 81"/>
        <circle cx="50" cy="50" r="12"/>
        <circle cx="50" cy="50" r="30" stroke-dasharray="3 6"/>
      </g>
    </symbol>

    <symbol id="g-runa1" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M50 8 V92 M50 22 L78 44 L50 66 M50 40 L24 60"/>
      </g>
    </symbol>

    <symbol id="g-runa2" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M28 10 L72 50 L28 90 M72 10 V90"/>
      </g>
    </symbol>

    <symbol id="g-runa3" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round">
        <path d="M50 8 L20 50 L50 92 L80 50 Z M50 8 V92 M20 50 H80"/>
      </g>
    </symbol>

    <symbol id="g-espiral" viewBox="0 0 100 100">
      <g fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
        <path d="M46 50 a4 4 0 0 1 8 0 a8 8 0 0 1 -16 0 a14 14 0 0 1 28 0 a20 20 0 0 1 -40 0 a26 26 0 0 1 52 0 a32 32 0 0 1 -64 0"/>
      </g>
    </symbol>
  </defs>
</svg>
