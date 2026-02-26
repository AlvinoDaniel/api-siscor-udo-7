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
                margin-top: 40px;
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
                text-align: center;
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
        </style>
    </head>

    <body >
    <div class="page-container">
    <div id="pageDocument" class="page page-shadow">
      <div class="page-content">
        <div>
            <div class="page-date">
                <span class="font-bold">{{$numero_oficio}}</span>
                <span>Cumaná, {{$fechaOficio}}</span>
            </div>
            <div class="page-header title-header">
                <span>OFICIO</span>
            </div>

            <div class="page-addressee">

                <span>Ciudadano(a):</span>
                <span class="font-bold">{{$destino->nombres_apellidos}}</span>
                <span class="font-bold">{{$destino->descripcion_cargo}}</span>
                <span>Su Despacho.- </span>
            </div>


          <div class="page-body"> {!! $contenido !!} </div>
          <div class="page-sincerely">
            <span>Atentamente,</span>
            <span class="page-user-signature">
                {{$remitente}}
            </span>
            <span>{{$documentoRemitente}}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

    </body>
</html>
