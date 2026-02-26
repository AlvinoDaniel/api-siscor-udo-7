<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Documento</title>
        <style>

            /* plus-jakarta-sans-200 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 200;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-200.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-300 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 300;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-300.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-regular - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 400;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-regular.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-500 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 500;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-500.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-600 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 600;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-600.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-700 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 700;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-700.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-800 - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: normal;
            font-weight: 800;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-800.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-200italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 200;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-200italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-300italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 300;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-300italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 400;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-400italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-500italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 500;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-500italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-600italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 600;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-600italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-700italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 700;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-700italic.woff') }}) format('woff2');
        }
        /* plus-jakarta-sans-800italic - latin */
        @font-face {
            font-family: 'Plus Jakarta Sans';
            font-style: italic;
            font-weight: 800;
            src: url({{ storage_path('fonts/plus-jakarta-sans-v3-latin-800italic.woff') }}) format('woff2');
        }

            @page {
                margin: 1cm 1.8cm;
                font-family: 'Plus Jakarta Sans', sans-serif;
                font-size: 11pt;
            }

            .page p {
                margin-bottom: 8px !important;
                text-align: justify;
            }

            .page-header {
                text-align: center;
                margin-bottom: 20px;
                font-weight: bold;
                line-height: 1.3rem;
                text-transform: uppercase;
            }

            .page-date {
                margin-bottom: 20px;
                text-align: right;
            }

            .page-header img {
                margin: 5px 10px;
            }
            span {
                display: block
            }

            .page-addressee {
                margin: 10px 0;
                display: -webkit-box !important;
                display: -ms-flexbox !important;
                display: flex !important;
                -webkit-box-orient: vertical !important;
                -webkit-box-direction: normal !important;
                -ms-flex-direction: column !important;
                flex-direction: column !important;
            }

            .page-body {
                margin: 10px 0;
                text-align: justify;
            }

            .page-copys {
                padding: 20px 0;
                text-align: justify;
                font-size: 10pt;
            }

            .page-sincerely {
                font-weight: bold;
                text-align: center;
                margin: 0 auto;
                padding-top: 16px !important;
                padding-bottom: 16px !important;

            }
            .page-footer {
                position: absolute;
                left: 0;
                bottom: -20px;
                font-size: 12px;
                width: 100%;
                text-align: center;
                margin: 0 auto;
            }

            .page-user-signature {
                position: relative;
                padding-left: 40px;
                padding-right: 40px;
                margin: 0 auto;
                padding-top: 10px;
                border-color: black;
                border-style: solid;
                width: 40%;
                border-width: 1px 0 0 0;
            }

.title-header {
  font-size: 1.2em;
}

.font-bold {
  font-weight: bold !important;
}

.font-medium {
  font-weight: 400 !important;
}

.font-uppercase {
  text-transform: uppercase !important;
}
    #watermark {
        position: fixed;
        top: 40%;
        left: 50%;
        width: 100%;
        text-align: center;
        font-size: 80pt !important;
        color: #979595;
        /* #D3D3D3; */
        opacity: 0.2;
        transform: translate(-50%, -50%) rotate(-30deg);
        z-index: 1000;
        font-family: 'Courier New', monospace !important;
        font-weight: bold;
        pointer-events: none;
        white-space: nowrap;
        text-transform: uppercase;
    }
        </style>
    </head>

    <body >
        <div id="watermark">
        Vista Previa
        </div>
    <div class="page-container">
    <div id="pageDocument" class="page page-shadow">
      <div class="page-content">
        <div>
          <div class="page-header">
            <img src="{{ storage_path('app/public/Logo_UDO.png') }}" width="70" height="68">
            <span>UNIVERSIDAD DE ORIENTE</span>
            <span>{{$dptoPropietario}}</span>
            <span>{{$nucleo}}</span>
          </div>
          <div class="page-date">
            <span class="font-bold">{{$dptoSiglas}} N° **** - {{$year}}</span>
            <span>Cumaná, {{$fechaEnviado}}</span>
          </div>
            @if($isCircular)
                <div class="page-header title-header">
                    <span>CIRCULAR</span>
                </div>
                <div class="page-addressee">
                <p>
                    <span style="display: inline;">Para:</span>
                    <span style="display: inline;" class="font-bold font-uppercase"> {{$destino}} </span>
                </p>
                <p>
                    <span style="display: inline;">De: </span>
                    <span style="display: inline;" class="font-bold font-uppercase">{{$dptoPropietario}} </span>
                </p>
                </div>
            @endIf

            @if($isOficio)
            <div class="page-addressee">

              <span>Ciudadano(a):</span>
              <span class="font-bold">{{$destino_nombres_apellidos}}</span>
              <span class="font-bold">{{$destino_descripcion_cargo}}</span>
              <span>Su Despacho.- </span>
            </div>
            @endIf

          <div class="page-body"> {!! $contenido !!} </div>
          <div class="page-sincerely">
            <span style="margin-bottom:5px; margin-bottom:15px">Atentamente,</span>
              <img
                src="{{$baseUrlFirma}}"
                width="200"
                style="margin-bottom:5px"
              >
            <span class="page-user-signature">
                {{$propietarioJefe}}
            </span>
            <span>{{$propietarioCargo}}</span>
          </div>
        </div>
         @if($hasCopias)
          <div class="page-copys">
            <span style="font-size:10px" class="font-uppercase font-medium">CC: {{$dptoCopias}}</span>
          </div>
          @endIf
      </div>
      <div class="page-footer">
        <span class="font-bold">DEL PUEBLO VENIMOS / HACIA EL PUEBLO VAMOS</span>
        <span style="font-size:10px">{{$nucleoDireccion}}</span>
      </div>
    </div>
  </div>

    </body>
</html>
