<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Twinspin</title>
    <meta name="google" value="notranslate"></meta>
    <meta name="viewport" content="width=device-width"></meta>
    <meta name="apple-mobile-web-app-capable" content="yes"></meta>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"></meta>
    <meta name="format-detection" content="telephone=no"></meta>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"></meta>
    <link rel="apple-touch-icon-precomposed" href="multimedia/images/icons/icon.png"></link>
    <meta name="msapplication-TileImage" content="multimedia/images/icons/icon.png"></meta>
    <style>
        @font-face {
            font-family: 'NetentStandardUI';
            src: url('../../src/multimedia/fonts/PFDinDisplayPro-Reg.woff') format('woff'),
            url('../common/multimedia/fonts/PFDinDisplayPro-Reg.woff') format('woff');
        }

        * {

            -webkit-touch-callout: none;

            -webkit-user-select: none;
            -ms-touch-action: none;
            touch-action: none;
        }

        html {
            background-color: black;
        }

        body {
            -webkit-user-select: none;
            -webkit-text-size-adjust: none;
            margin: 0;
            padding: 0;
            background-color: #080807;
            font-family: NetentStandardUI, Tahoma, sans-serif;
            font-size: 16px;
        }

        #viewport {
            position: absolute;
            z-index: 1;
            top: 0;
            left: 0;
            visibility: visible;
            background-color: black;
            overflow: visible;
        }

        #rootWrapper {
            position: relative;
            top: 0;
            left: 0;
            overflow: visible;
            z-index: 2;
            background-color: black;
        }

        #gameWrapper {
            width: 100%;
            height: 100%;
        }

        #gamewrapper, .windowBlind {
            position: absolute;
            top: auto;
            left: auto;
            overflow: hidden;
            z-index: 0;
        }

        #gameWrapper.hideGame > :not(.dialogWindowWrapper) {
            display: none !important;
        }

        #portrait_message {
            display: none;
        }

        .oldLoaderBackground {
            width: 100%;
            height: 100%;
            background-color: #000000;
            position: relative;
            z-index: 3;
        }

        .oldLoaderWrapper {
            position: absolute;
            top: 0;
            left: 0;
            overflow-y: visible;
            z-index: 4;
            background-color: #000000;
        }

        #loaderCanvas {
            position: absolute;
            left: 0;
            z-index: 2;
        }

        .logoWrapper_desktop {
            -moz-transform: scale(0.75, 0.75);
            -o-transform: scale(0.75, 0.75);
            -webkit-transform: scale(0.75, 0.75);
            transform: scale(0.75, 0.75);
        }

        .button {
            height: 25%;
            padding-left: 2.5%;
            padding-right: 2.5%;

            display: inline-block;
            cursor: pointer;

            color: #000000;
            font-size: 0.9em;
            font-family: NetentStandardUI, Tahoma, sans-serif;
            -webkit-transition: opacity 50ms linear;

            border-radius: 40px;
            background-color: #CCCCCC;
        }

        .button:active {
            background-color: #999999;
        }

        .button_disabled {
            cursor: default;
        }

        .oldLoaderWrapper #loadDialogWrapper {
            position: absolute;
            left: 0;
            top: 290px;
            overflow: visible;
            z-index: 5;
            background-color: #000;
            width: 960px;
            height: 300px;
            min-height: 300px;
        }

        .oldLoaderWrapper #loadDialogGUI {
            position: relative;
            text-align: center;
        }

        .oldLoaderWrapper #loadDialogGUI .button {
            height: 74px;
            line-height: 74px;
            font-size: 1.8em;
        }

        .oldLoaderWrapper #loadDialogGUI .button {
            min-width: 230px;
            padding-left: 10px;
            padding-right: 10px;
            margin-right: 5px;
            margin-left: 5px;
        }

        .oldLoaderWrapper #loadDialogTextWrap {
            margin-top: 20px;
            position: relative;
            text-align: center;
            font-size: 24px;
            color: white;
            min-height: 146px;
        }

        .oldLoaderWrapper #loadDialogTextWrap .loadDialogText {
            margin: 14px auto;
            max-width: 505px;
            text-align: center;
        }

        .oldLoaderWrapper #logoCard {

            -moz-transition: top 0.3s;
            -o-transition: top 3s;
            -webkit-transition: top 0.3s;
            transition: top 0.3s;

            position: absolute;
            top: 0;

            z-index: 5;
        }

        .hidden {
            visibility: hidden;
        }

        .scrollup {
            width: 100%;
            z-index: 9999;
            position: absolute;
            background: rgba(173, 216, 230, 0.5) no-repeat;
            background-size: 100%;
            top: -500px;
            left: 0;
            margin: 0;
            padding: 0;

            -webkit-animation: visibility 0.3s both;
            -webkit-transform: translate3D(0, 0, 0);
        }

        .scrollAnimationContainer {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 10000;
            background-repeat: no-repeat;
            background-position: 60% 40px;
            background-size: 100px;
            top: 0;
            left: 0;
        }

        .mobile_portrait .scrollAnimationContainer {
            background-position: 60% 80px;
        }

        .gcmMode #loaderCanvas, .gcmMode #logoWrapper {
            opacity: 0.01;
        }

        #loaderBackground {

        }

        .loaderBackground {
            width: 100%;
            height: 100%;
            background-color: #080807;
            position: relative;
            z-index: 3;
        }

        #loaderWrapper {

            display: none;
        }

        .loaderWrapper {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 4;
            background-color: #080807;
            color: white;
            overflow-y: visible;
        }

        .loaderBar {
            position: relative;
            top: 44%;
            background-color: #3a3a3a;
            width: 420px;
            height: 8px;
            overflow: hidden;
            z-index: 4;
            margin-right: auto;
            margin-left: auto;
        }

        .tablet_portrait .loaderBar,
        .tablet_landscape .loaderBar,
        .mobile_portrait .loaderBar,
        .mobile_landscape .loaderBar {
            width: 562px;
        }

        .tablet_portrait .loaderBar,
        .mobile_portrait .loaderBar {
            height: 12px;
        }

        .tablet_portrait .branded .loaderBar,
        .mobile_portrait .branded .loaderBar {
            top: 25%;
        }

        .tablet_landscape .branded .loaderBar,
        .mobile_landscape .branded .loaderBar {
            top: 36%;
        }

        .loaderBarProgress {
            width: 0;
            height: 100%;
            background-color: #78be20;

            -moz-transition: width 600ms linear;
            -o-transition: width 600ms linear;
            -webkit-transition: width 600ms linear;
            transition: width 600ms linear;

            -webkit-transform: translateZ(0);
            -moz-transform: translateZ(0);
            -ms-transform: translateZ(0);
            -o-transform: translateZ(0);
            transform: translateZ(0);
        }

        .error .loaderBarProgress {
            background-color: #A9202C;

            -moz-transition: none;
            -o-transition: none;
            -webkit-transition: none;
            transition: none;
        }

        .dialogAndLogoWrapper {
            position: relative;
            top: 44%;
            height: 300px;
            overflow: hidden;
            padding-top: 40px;
            z-index: 2;
        }

        .tablet_portrait .branded .dialogAndLogoWrapper,
        .mobile_portrait .branded .dialogAndLogoWrapper {
            top: 25%;
        }

        .tablet_landscape .branded .dialogAndLogoWrapper,
        .mobile_landscape .branded .dialogAndLogoWrapper {
            top: 36%;
        }

        .loadDialogWrapper {
            position: absolute;
            top: 100%;
            left: 50%;
            margin-left: -300px;
            padding-top: 20px;
            min-height: 300px;
            width: 600px;
            background-color: #080807;
            overflow: visible;

            -moz-transition: top 0.3s cubic-bezier(0, 0, 0.2, 1);
            -o-transition: top 0.3s cubic-bezier(0, 0, 0.2, 1);
            -webkit-transition: top 0.3s cubic-bezier(0, 0, 0.2, 1);
            transition: top 0.3s cubic-bezier(0, 0, 0.2, 1);

            transform: translate3d(0, 0, 0);
        }

        .loadDialogVisible {
            visibility: visible;
        }

        .loadDialogGUI {
            position: relative;
            text-align: center;
            margin-top: 20px;
        }

        .loadDialogGUI .button {
            height: 74px;
            line-height: 74px;
            font-size: 1.8em;
        }

        .loadDialogGUI .button {
            min-width: 230px;
            padding-left: 10px;
            padding-right: 10px;
            margin-right: 5px;
            margin-left: 5px;
        }

        .loadDialogTextWrap {
            margin-top: 20px;
            position: relative;
            text-align: center;
            font-size: 24px;
            color: white;
            min-height: 146px;
        }

        .loadDialogTextWrap .loadDialogText {
            margin: 14px auto;
            max-width: 505px;
            text-align: center;
        }

        #logoCard {

            height: 100%;

            position: absolute;
            top: 0;

            z-index: 5;
        }

        .brandingWrapper {
            height: 40px;
            margin-left: auto;
            margin-right: auto;
            overflow: hidden;
            position: relative;
            top: 44%;
            margin-top: -115px;
            z-index: 1;

            opacity: 0;
            filter: alpha(opacity=0);

            -moz-transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
            -o-transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
            -webkit-transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
            transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
        }

        .tablet_portrait .brandingWrapper,
        .mobile_portrait .brandingWrapper {
            margin-top: -100px;
            height: 60px;
            top: 25%;
        }

        .tablet_landscape .brandingWrapper,
        .mobile_landscape .brandingWrapper {
            top: 30%;
        }

        .brandingWrapper.animate {
            opacity: 1;
            filter: alpha(opacity=100);

            -webkit-transition-delay: 1.2s;
            transition-delay: 1.2s;
        }

        .brandingWrapper > * {
            display: inherit;
            height: 100%;
            margin-right: auto;
            margin-left: auto;
        }

        .logoWrapper {
            width: 210px;
            height: 75px;
            position: relative;
            left: 50%;
            margin: 0 0 0 -105px;
        }

        .tablet_portrait .logoWrapper,
        .mobile_portrait .logoWrapper {
            width: 316px;
            height: 113px;
            margin: 0 0 0 -158px;
        }

        .logoSVG {
            width: 100%;
        }

        .logoPartsFill {
            fill: #FFFFFF;
            fill-opacity: 1.0;
            stroke: none;
        }

        .logoLine {
            opacity: 0;
            filter: alpha(opacity=0);
            transform: scale(1, 0);
        }

        .netWrapper,
        .logoLine,
        .entWrapper {
            -moz-transition: transform 0.9s cubic-bezier(0.100, 1, 0.590, 1), opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1);
            -o-transition: transform 0.9s cubic-bezier(0.100, 1, 0.590, 1), opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1);
            -webkit-transition: transform 0.9s cubic-bezier(0.100, 1, 0.590, 1), opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1);
            transition: transform 0.9s cubic-bezier(0.100, 1, 0.590, 1), opacity 0.9s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }

        .netWrapper {
            -webkit-transform: translate(135px);
            -moz-transform: translate(135px);
            -ms-transform: translate(135px);
            -o-transform: translate(135px);
            transform: translate(135px);
        }

        .entWrapper {
            -webkit-transform: translate(-123px);
            -moz-transform: translate(-123px);
            -ms-transform: translate(-123px);
            -o-transform: translate(-123px);
            transform: translate(-123px);
        }

        .logoWrapper.animate .entWrapper,
        .logoWrapper.animate .netWrapper {
            opacity: 1;
            filter: alpha(opacity=100);

            -webkit-transition-delay: 0.7s;
            transition-delay: 0.7s;
            -moz-transform: translate(0);
            -o-transform: translate(0);
            -webkit-transform: translate(0);
            transform: translate(0);
        }

        .logoWrapper.animate .logoLine {
            opacity: 1;
            filter: alpha(opacity=100);
            transform: scale(1, 1);
        }

        .logoWrapper.IEBrowser .logoLine {
            transform: scale(1, 1);
        }

        .logoWrapper.IEBrowser .entWrapper,
        .logoWrapper.IEBrowser .netWrapper {
            opacity: 0;
            filter: alpha(opacity=0);
            -webkit-transform: translate(0);
            -moz-transform: translate(0);
            -ms-transform: translate(0);
            -o-transform: translate(0);
            transform: translate(0);
        }

        .logoWrapper.IEBrowser.animate .entWrapper,
        .logoWrapper.IEBrowser.animate .netWrapper {
            opacity: 1;
            filter: alpha(opacity=100);

            -webkit-transition-delay: 0s;
            transition-delay: 0s;
        }

        .sloganWrapper {
            position: relative;
            left: 50%;
            margin-left: -80px;
            width: 160px;
            height: 18px;
            padding: 10px 0 0 0;
        }

        .tablet_portrait .sloganWrapper,
        .mobile_portrait .sloganWrapper {
            width: 240px;
            height: 27px;
            margin-left: -120px;
        }

        .sloganSVG {
            width: 100%;
            opacity: 0;
            filter: alpha(opacity=0);

            -moz-transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
            -o-transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
            -webkit-transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
            transition: opacity 0.5s cubic-bezier(0.100, 1, 0.590, 1);
        }

        .sloganWrapper.animate .sloganSVG {
            opacity: 1;
            filter: alpha(opacity=100);

            -webkit-transition-delay: 1.2s;
            transition-delay: 1.2s;
        }

        .betterGamingSvg.end {
            opacity: 1;
            filter: alpha(opacity=100);
        }

        .fillWhite {
            fill: white;
        }
    </style>
    <style type="text/css">
        @font-face {
            font-family: "NetentStandardUI";
            src: url('multimedia/fonts/PFDinDisplayPro-Reg.otf'),
            url('multimedia/fonts/PFDinDisplayPro-Reg.woff') format('woff');
        }

        @font-face {
            font-family: "NETENT_MyriadPro";
            src: url('multimedia/fonts/NETENT_MyriadPro-Bold.otf'),
            url('multimedia/fonts/NETENT_MyriadPro-Bold.woff') format('woff');
        }

        @font-face {
            font-family: "NETENT_MyriadProReg";
            src: url('multimedia/fonts/NETENT_MyriadPro-Regular.otf'),
            url('multimedia/fonts/NETENT_MyriadPro-Regular.woff') format('woff');
        }

        @font-face {
            font-family: "Impact";
            src: url('multimedia/fonts/impact.otf'),
            url('multimedia/fonts/impact.ttf'),
            url('multimedia/fonts/impact.woff') format('woff');
        }

    </style>
</head>
<body>
<div id="gameBackground"></div>
<div id="loaderBackground"></div>
<div id="loaderWrapper"></div>
<div id="viewport">
    <div id="gameWrapper">
        <div id="gameElements"></div>
    </div>
</div>

<script src="javascript/init.js?v=<?= time() ?> " type="text/javascript" charset="utf-8"></script>
<div id="fontLoader">
    <div style="font-family: Impact">.</div>
    <div style="font-family: NetentStandardUI">.</div>
    <div style="font-family: NETENT_MyriadPro">.</div>
    <div style="font-family: NETENT_MyriadProReg">.</div>
</div>
</body>
</html>