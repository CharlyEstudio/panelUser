<?xml version="1.0"?>
<REPORTES FECHA="&quot;2017-01-06&quot;">
<REPORTE REPID="209" TEXTOS="&lt;?xml version=&quot;1.0&quot;?&gt;
&lt;REPORTE CLASE=&quot;T&quot; BASE=&quot;DOC&quot; CONDICIONORIGEN=&quot;&quot; FILTROID=&quot;0&quot; ANTERIORID=&quot;0&quot; ANCHO=&quot;999&quot; LARGO=&quot;0&quot; TAMLETRA=&quot;0&quot; IMPRESORA=&quot;&quot; COPIAS=&quot;1&quot; INICIAL=&quot;0&quot; FINAL=&quot;0&quot; OPCIONES=&quot;0&quot; DESTINO=&quot;C&quot; TITULO=&quot;FACTURA A CORREO J&quot; CATEGORIA=&quot;CFDI NUEVOS&quot; LIMITES=&quot;R&quot; NIVEL=&quot; &quot; GRUPO=&quot; &quot;&gt;
&lt;TEXTO CONTENIDO=&quot;&amp;lt;!DOCTYPE html&amp;gt;
&amp;lt;html&amp;gt;
   &amp;lt;head&amp;gt;
      &amp;lt;title&amp;gt;CFDI&amp;lt;/title
   &amp;lt;/head&amp;gt;
 &amp;lt;body&amp;gt;
&amp;lt;table border=&amp;quot;0&amp;quot; width=&amp;quot;100%&amp;quot;&amp;gt;
^ADJUNT {CFD.CFD XML ARCHIVO}
^DESTIN {CLI.EMAIL}\{CLI.NOMBRE CL.}\SI
^ARCHIV {CFD_PDF_ARCHIVO}
^GENECB NUMF\{NUMERO1}\V\128\P\90\54
^PARAME -x 1
&amp;lt;&amp;amp;SQ(&amp;apos;UPDATE DOC SET XIMPRESION=XIMPRESION+1 WHERE DOCID={ID_DOCUMENTO}&amp;apos;) &amp;amp;&amp;gt;
&amp;lt;colgroup&amp;gt;
 &amp;lt;col width=&amp;quot;65%&amp;quot;&amp;gt;
 &amp;lt;col width=&amp;quot;35%&amp;quot;&amp;gt;
&amp;lt;/colgroup&amp;gt;
   &amp;lt;tr&amp;gt;
       &amp;lt;td&amp;gt; &amp;lt;img src=&amp;quot;/home/ferrum/ferrumop/salida/logo.jpg&amp;quot; alt=&amp;quot;logo&amp;quot; width=&amp;quot;320&amp;quot;&amp;gt; 
&amp;lt;/td&amp;gt;
^MUESCB NUMF\CODIGO\0\0
       &amp;lt;td bgcolor=&amp;quot;#FFFFFF&amp;quot;&amp;gt;
         &amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt;
          {CFD.EMI.NOMBRE}&amp;lt;br&amp;gt;
          {CFD.EMI. DOM.CALLE} No.{CFD.EMI. DOM.NUMERO} {CFD.EMI. DOM.INTERIOR} Col. {CFD.EMI. DOM.COLONIA}&amp;lt;BR&amp;gt;
          C.P.{CFD.EMI. DOM.CP}  {CFD.EMI. DOM.LOCALIDAD},{CFD.EMI. DOM.ESTADO}&amp;lt;BR&amp;gt;
          &amp;lt;b&amp;gt;R.F.C.&amp;lt;/b&amp;gt; {CFD.EMI.RFC}&amp;lt;BR&amp;gt;
              FECHA Y HORA: { FECHA1 } { HORA } &amp;lt;BR&amp;gt;
              LUGAR DE EXPEDICION: {CFD.CFD LUGAR EXPEDICION}&amp;lt;BR&amp;gt;
              FOLIO DEL SAT: {CFD.CFDI UUID}&amp;lt;BR&amp;gt;
              FECHA DE CERTIFICACION: {  CFDI_FECHA_TIMBRADO  }&amp;lt;BR&amp;gt;
              CERTIFICADO EMISOR: {CFD.CFDI CERTIFICADO NUM. SAT} &amp;lt;BR&amp;gt;
              CERTIFICADO SAT:  &amp;lt;BR&amp;gt;
                              FECHA RE-IMPRESION: {FECHAreimp}&amp;lt;BR&amp;gt;             
            &amp;lt;/font&amp;gt;
       &amp;lt;/td&amp;gt;
   &amp;lt;/tr&amp;gt;
&amp;lt;/table&amp;gt;

&amp;lt;!-- Inicia datos del cliente --&amp;gt;
&amp;lt;table border=&amp;quot;1&amp;quot; width=&amp;quot;100%&amp;quot;&amp;gt;
&amp;lt;colgroup&amp;gt;
   &amp;lt;col width=&amp;quot;65%&amp;quot;&amp;gt;
   &amp;lt;col width=&amp;quot;35%&amp;quot;&amp;gt;
&amp;lt;/colgroup&amp;gt;
   &amp;lt;tr&amp;gt;
       &amp;lt;td&amp;gt; &amp;lt;!-- Val.1x1 --&amp;gt;
        &amp;lt;FONT SIZE=&amp;quot;1&amp;quot;&amp;gt;
             &amp;lt;B&amp;gt;CLIENTE: {NUMERO} &amp;lt;/b&amp;gt; &amp;lt;br&amp;gt;
             &amp;lt;B&amp;gt;{CFD.REC.NOMBRE}&amp;lt;/B&amp;gt;  &amp;lt;BR&amp;gt;
               {CFD.REC. CALLE} No.{CFD.REC. NUMERO} {CFD.REC. INTERIOR}&amp;lt;BR&amp;gt;
             {CFD.REC. COLONIA}  C.P. {CFD.REC. CP} &amp;lt;BR&amp;gt; {CFD.REC. LOCALIDAD} {CFD.REC. ESTADO} &amp;lt;BR&amp;gt;
             R.F.C. {CFD.REC.RFC} 
             VENDEDOR: {CLAVE}&amp;lt;BR&amp;gt;         
&amp;lt;/FONT&amp;gt;
       &amp;lt;/td&amp;gt;
       &amp;lt;td bgcolor=&amp;quot;#FFFFF&amp;quot; width=&amp;quot;10%&amp;quot;&amp;gt; &amp;lt;!-- Val.2x1 --&amp;gt;
         &amp;lt;FONT SIZE=&amp;quot;1&amp;quot;&amp;gt;
             PAGO EN UNA SOLA EXHIBICION&amp;lt;BR&amp;gt;
             &amp;lt;b&amp;gt;METODO DE PAGO:&amp;lt;/b&amp;gt; {CFD.CFD METODO PAGO}/ {CFD.CFD CUENTA PAGO}
          &amp;lt;b&amp;gt;   ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI&amp;lt;/b&amp;gt; &amp;lt;br&amp;gt;
                REGIMEN FISCAL EMISOR: {                             EMI._REGIMEN_FISCAL                              }&amp;lt;/b&amp;gt;&amp;lt;br&amp;gt;  
                EFECTOS FISCALES AL PAGO &amp;lt;br&amp;gt;
   &amp;lt;/FONT&amp;gt;
       &amp;lt;/td&amp;gt;
   &amp;lt;/tr&amp;gt;
&amp;lt;/table&amp;gt;
&amp;lt;!-- Termina datos del cliente --&amp;gt;

&amp;lt;!-- Inicia CUERPO DEL DOCUMENTO --&amp;gt;
&amp;lt;table border=&amp;quot;1&amp;quot; width=&amp;quot;100%&amp;quot;&amp;gt;
   &amp;lt;tr bgcolor=&amp;quot;#FFFFFF&amp;quot;&amp;gt;
    &amp;lt;th&amp;gt;&amp;lt;!-- Titulo 1 --&amp;gt;
            Producto
    &amp;lt;/th&amp;gt;  
     &amp;lt;th&amp;gt; &amp;lt;!-- Titulo 1 --&amp;gt;
            Codigo
     &amp;lt;/th&amp;gt;
       &amp;lt;th&amp;gt;&amp;lt;!-- Titulo 2 --&amp;gt;
            Descripcion
     &amp;lt;/th&amp;gt;
      &amp;lt;th&amp;gt;&amp;lt;!-- Titulo 3 --&amp;gt;
            Cantidad
     &amp;lt;/th&amp;gt;
       &amp;lt;th&amp;gt; &amp;lt;!-- Titulo 4 --&amp;gt;
            Unidad
       &amp;lt;/th&amp;gt;
       &amp;lt;th&amp;gt; &amp;lt;!-- Titulo 5 --&amp;gt;
              Precio U.
        &amp;lt;/th&amp;gt;   
     &amp;lt;th&amp;gt;     &amp;lt;!-- Titulo 5 --&amp;gt;
            Desc.
        &amp;lt;/th&amp;gt;  
      &amp;lt;th&amp;gt; &amp;lt;!-- Titulo 6 --&amp;gt;
            Importe
         &amp;lt;/th&amp;gt; 
   &amp;lt;/tr&amp;gt;
®INICIO¯

&amp;lt;!-- Desde aqui no se deben ni insertar ni eliminar ENTERS--&amp;gt;
&amp;lt;tr&amp;gt;
&amp;lt;td align=&amp;quot;left&amp;quot;&amp;gt; &amp;lt;!--Col.4--&amp;gt; &amp;lt;font size=&amp;quot;0&amp;quot;&amp;gt; {_DOCPAR.CLAVE}  &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;
&amp;lt;td align=&amp;quot;left&amp;quot;&amp;gt; &amp;lt;!--Col.4--&amp;gt; &amp;lt;font size=&amp;quot;0&amp;quot;&amp;gt; {CODIGO_FABRICAN}  &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;
&amp;lt;td align=&amp;quot;left&amp;quot;&amp;gt; &amp;lt;!--Col.4--&amp;gt; &amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt; {_DOCPAR.CONCEPTO} &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;
&amp;lt;td align=&amp;quot;left&amp;quot;&amp;gt; &amp;lt;!--Col.1--&amp;gt; &amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt; {_DOCPAR.CANTIDAD} &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;
&amp;lt;td align=&amp;quot;left&amp;quot;&amp;gt; &amp;lt;!--Col.1--&amp;gt; &amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt;  {_DOCPAR.UNIDAD} &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;
&amp;lt;td align=&amp;quot;right&amp;quot;&amp;gt; &amp;lt;!--Col.5--&amp;gt; &amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt; {_DOCPAR.PRECIO UNITARIO}  &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;
&amp;lt;td align=&amp;quot;left&amp;quot;&amp;gt; &amp;lt;!--Col.4--&amp;gt; &amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt; {DESCUENTO} &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;
&amp;lt;td align=&amp;quot;right&amp;quot;&amp;gt; &amp;lt;!--Col.6--&amp;gt; &amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt; {  IMPORTE   }  &amp;lt;/font&amp;gt; &amp;lt;/td&amp;gt;&amp;lt;/tr&amp;gt;
&amp;lt;!-- Hasta aqui ya se pueden insertar o eliminar ENTERS--&amp;gt;
®FIN¯
   &amp;lt;tr bgcolor=&amp;quot;#FFFFFF&amp;quot;&amp;gt;
       &amp;lt;td COLSPAN=&amp;quot;6&amp;quot;&amp;gt; &amp;lt;!-- Total 1 --&amp;gt;
            Importe con Letra &amp;lt;BR&amp;gt;
            {DOC.TOTAL LETRA}
       &amp;lt;/td&amp;gt;
       &amp;lt;td ALIGN=&amp;quot;RIGHT&amp;quot;&amp;gt; &amp;lt;!-- Total 5 --&amp;gt;
            SubTotal&amp;lt;BR&amp;gt;
             IVA 16%&amp;lt;BR&amp;gt;
               Total
       &amp;lt;/td&amp;gt;
       &amp;lt;td ALIGN=&amp;quot;RIGHT&amp;quot;&amp;gt; &amp;lt;!-- Total 6 --&amp;gt;
            {  SUBTOTAL  }&amp;lt;BR&amp;gt;
            {DOC.IVA}&amp;lt;BR&amp;gt;
            {   TOTAL    }
       &amp;lt;/td&amp;gt;
   &amp;lt;/tr&amp;gt;
&amp;lt;/table&amp;gt;
&amp;lt;!-- Termina CUERPO DEL DOCUMENTO --&amp;gt;

&amp;lt;P ALIGN=&amp;quot;CENTER&amp;quot;&amp;gt;
&amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt;
&amp;lt;B&amp;gt; {DOC.NOTA} &amp;lt;BR&amp;gt; &amp;lt;BR&amp;gt; &amp;lt;B&amp;gt;***NO SE ACEPTAN DEVOLUCIONES DESPUES DE 30 DIAS DE HABER RECIBIDO EL MATERIAL.&amp;lt;/B&amp;gt;&amp;lt;BR&amp;gt;
&amp;lt;B&amp;gt;*** TODA DEVOLUCION TIENE UN CARGO DEL 20% POR MANEJO DE MATERIAL, PAGO DE CONTADO.&amp;lt;br&amp;gt;
&amp;lt;/font&amp;gt;
&amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt; CUENTA BANCARIA: BANCOMER BBVA 0107301790 CLABE 012680001073017903&amp;lt;br&amp;gt; A NOMBRE DE FERREMAYORISTAS OLVERA S.A. DE C.V. &amp;lt;br&amp;gt;
AGRADECEREMOS CONFIRMAR Y/O ENVIAR SU COMPROBANTE DE TRANSFERENCIA AL DEPARTAMENTO DE CREDITO Y COBRANZA A LA CUENTA DE CORREO:&amp;lt;/font&amp;gt;&amp;lt;b&amp;gt; 
cxc-qro@ferremayoristas.com.mx&amp;lt;/P&amp;gt;

&amp;lt;!-- Inicia CADENAS SAT --&amp;gt;
&amp;lt;table border=&amp;quot;1&amp;quot; width=&amp;quot;100%&amp;quot;&amp;gt;
   &amp;lt;tr&amp;gt;
       &amp;lt;td&amp;gt; &amp;lt;!-- Val.1x1 --&amp;gt;
             &amp;lt;img src=&amp;quot;../{                                                                                                                                             CFD_PNG_ARCHIVO                                                                                                                                              }&amp;quot; alt=&amp;quot;CBB&amp;quot; width=&amp;quot;190&amp;quot; height=&amp;quot;190&amp;quot;&amp;gt;
       &amp;lt;/td&amp;gt;
       &amp;lt;td fontsize=&amp;apos;1&amp;apos;&amp;gt; &amp;lt;!-- Val.2x1 --&amp;gt;
          SELLO DIGITAL:  {  CFD.CFD SELLO  }&amp;lt;BR&amp;gt;
          SELLO DEL SAT:  { CFD.CFDI SELLO SAT }&amp;lt;BR&amp;gt; 
          CADENA: &amp;lt;BR&amp;gt; {                      CFD.CFD CADENA                      }     
       &amp;lt;/td&amp;gt; &amp;lt;/font&amp;gt;
&amp;lt;/tr&amp;gt;
&amp;lt;Tr&amp;gt;
&amp;lt;td colspan=&amp;quot;2&amp;quot;&amp;gt;
&amp;lt;font size=&amp;quot;1&amp;quot;&amp;gt;
&amp;lt;b&amp;gt; { NUMDC  } &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; PAGARE  &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;BUENO POR: {   TOTAL    }&amp;lt;/b&amp;gt; &amp;lt;BR&amp;gt; 
 &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;{CFD.CFD LUGAR EXPEDICION},CP {CFD.EMI. DOM.CP} a {CFD.CFD FECHA}&amp;lt;br&amp;gt;
EN CUALQUIER LUGAR DE ESTE DOCUMENTO QUE SEA ESTAMPADA A FIRMA, 
POR ESTE PAGARE DEBO(EMOS) Y PAGARE(EMOS) INCONDICIONAL, SOLIDARIA 
Y MANCOMUNADAMENTE POR ESTE PAGARE EN {CFD.CFD LUGAR EXPEDICION} A LA
VISTA Y/O A LA ORDEN A FAVOR DE {CFD.EMI.NOMBRE} Y/O SUS REPRESENTANTES 
LEGALES EN PROLONGACION CONSTIUYENTES NUM.1095-2 COLONIA EL POCITO 
LA CANTIDAD DE {   TOTAL    } {DOC.TOTAL LETRA} VALOR RECIBIDO A MI 
ENTERA SATISFACCION, PACTANDO 5% DE INTERESES MENSUAL EN CASO DE NO 
HABER SIDO PAGADO INTEGRAMENTE ESTE PAGARE EL DIA {VENCIMIENTO}.&amp;lt;BR&amp;gt;
 &amp;lt;p&amp;gt;&amp;lt;B&amp;gt;NOMBRE Y DATOS DEL DEUDOR &amp;lt;/B&amp;gt;  &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; {CFD.REC.NOMBRE} &amp;amp;nbsp;&amp;amp; &amp;lt;br&amp;gt; 
&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;  &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;{CFD.REC. CALLE} {CFD.REC. NUMERO}&amp;lt;BR&amp;gt;  &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;
&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;{ REC._LOCALIDAD} &amp;lt;br&amp;gt; 
&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; telefono &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp; &amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;&amp;amp;nbsp;FIRMA:_________________________
 &amp;lt;br&amp;gt;.
&amp;lt;/td&amp;gt;
&amp;lt;/font&amp;gt;
&amp;lt;/tr&amp;gt;
&amp;lt;/table&amp;gt;
&amp;lt;!-- Termina CADENAS SAT --&amp;gt;
   &amp;lt;/body&amp;gt;
&amp;lt;/html&amp;gt;
&quot; MARSUP=&quot;0&quot; MARIZQ=&quot;0&quot; MARDER=&quot;0&quot; CALIDAD=&quot;0&quot; JUSTIFICACION=&quot;I&quot; TAM=&quot;N&quot;/&gt;
&lt;/REPORTE&gt;

" ANTERIORID="0" FILTROID="0" ENCABEZADOID="0" CREADO="2012-12-11" MODIFICADO="2016-12-23" ULTUSO="2017-01-06" TITULO="IMPRESION HOJA MEMBRETADA BUEN" CATEGORIA="FORMATOS SQL" INICIAL="" FINAL="" LIMINF="" LIMSUP="" IMPRESORA="8" LARGO="0" DESTINO="T" NIVEL="" GRUPO="F" OPCIONES="" ANCHO="999" COPIAS="1" IMPRESIONES="1" ORIENTACION="" TAMLETRA="0" BASE="DOC" LLAVE="DOC.DOCID" CONDICIONORIGEN="DOC.TIPO=&quot;F&quot; AND DOC.SERIEID=10" RELACIONCAMPOS="DOC  LEFT JOIN CFD AS RDOC1 ON DOC.DOCID=RDOC1.DOCID AND RDOC1.TIPDOC&lt;&gt;&quot;N&quot; LEFT JOIN CLI AS RDOC2 ON DOC.CLIENTEID=RDOC2.CLIENTEID  LEFT JOIN PER AS RDOC21 ON RDOC2.VENDEDORID=RDOC21.PERID  LEFT JOIN DOM AS RDOC22 ON RDOC2.DOMID=RDOC22.DOMID  LEFT JOIN TEL AS RDOC221 ON RDOC22.DOMID=RDOC221.DOMID LEFT JOIN DES AS RDOC3 ON DOC.DOCID=RDOC3.DESDOCID  LEFT JOIN UNIDADES AS RDOC31 ON RDOC3.DESUNIID=RDOC31.UNIDADID  LEFT JOIN INV AS RDOC32 ON RDOC3.DESARTID=RDOC32.ARTICULOID  LEFT JOIN DESDOC AS RDOC33 ON RDOC3.DESID=RDOC33.DESID" REPFROM="DOC LEFT JOIN DOM ON DOC.DOMDOCID=DOM.DOMID LEFT JOIN CLI ON DOC.CLIENTEID=CLI.CLIENTEID LEFT JOIN PER ON DOC.COBRADORID=PER.PERID" REPWHERE="DOC.TIPO=&quot;F&quot; AND DOC.SERIEID=10" REPORDER="DOC.NUMERO DESC,DOC.NUMERO DESC" LIMITES="R" CAMPOLIMITE="" FAMILIA="CFDI" CLASE="T" OBS="[137I] [138I] [137I] [138I]">
<CAMPO CRID="4473" CRREPID="209" CRCAMID="1480" CRNOMBRE="CFD.CFD XML ARCHIVO" CRNOMBRELARGO="C.F.D..CFD XML ARCHIVO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;ARCHIVOXML&quot;)" CRANCHO="120" CRDECIMAL="6" CRTIPO="C" CRALINEACION="" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4474" CRREPID="209" CRCAMID="2100" CRNOMBRE="CLI.EMAIL" CRNOMBRELARGO="CLIENTE.ID CLIENTE" CRRELACION="LEFT JOIN CLI ON $.CLIENTEID=^.CLIENTEID#" CRSQL="^.CLIENTEID" CRCODIGOSQL="RDOC2.CLIENTEID" CRPROCESO="" CRANCHO="5" CRDECIMAL="0" CRTIPO="N" CRALINEACION="" CRFORMATO="3" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4475" CRREPID="209" CRCAMID="2150" CRNOMBRE="CLI.NOMBRE CL." CRNOMBRELARGO="CLIENTE.NOMBRE CL." CRRELACION="LEFT JOIN CLI ON $.CLIENTEID=^.CLIENTEID#" CRSQL="^.NOMBRE" CRCODIGOSQL="RDOC2.NOMBRE" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4476" CRREPID="209" CRCAMID="1750" CRNOMBRE="CFD.EMI.NOMBRE" CRNOMBRELARGO="C.F.D..EMI.NOMBRE" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EMINOMBRE&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4477" CRREPID="209" CRCAMID="1540" CRNOMBRE="CFD.EMI. DOM.CALLE" CRNOMBRELARGO="C.F.D..EMI. DOM.CALLE" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;DFCALLE&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4478" CRREPID="209" CRCAMID="1610" CRNOMBRE="CFD.EMI. DOM.NUMERO" CRNOMBRELARGO="C.F.D..EMI. DOM.NUMERO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;DFNUMERO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4479" CRREPID="209" CRCAMID="1580" CRNOMBRE="CFD.EMI. DOM.INTERIOR" CRNOMBRELARGO="C.F.D..EMI. DOM.INTERIOR" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;DFINTERIOR&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4480" CRREPID="209" CRCAMID="1550" CRNOMBRE="CFD.EMI. DOM.COLONIA" CRNOMBRELARGO="C.F.D..EMI. DOM.COLONIA" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;DFCOLONIA&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4481" CRREPID="209" CRCAMID="1560" CRNOMBRE="CFD.EMI. DOM.CP" CRNOMBRELARGO="C.F.D..EMI. DOM.CP" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;DFCP&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4482" CRREPID="209" CRCAMID="1590" CRNOMBRE="CFD.EMI. DOM.LOCALIDAD" CRNOMBRELARGO="C.F.D..EMI. DOM.LOCALIDAD" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;DFLOCALIDAD&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4483" CRREPID="209" CRCAMID="1570" CRNOMBRE="CFD.EMI. DOM.ESTADO" CRNOMBRELARGO="C.F.D..EMI. DOM.ESTADO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;DFESTADO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4484" CRREPID="209" CRCAMID="1760" CRNOMBRE="CFD.EMI.RFC" CRNOMBRELARGO="C.F.D..EMI.RFC" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EMIRFC&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4485" CRREPID="209" CRCAMID="1360" CRNOMBRE="CFD.CFD LUGAR EXPEDICION" CRNOMBRELARGO="C.F.D..CFD LUGAR EXPEDICION" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EXPEDICION&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4486" CRREPID="209" CRCAMID="1640" CRNOMBRE="CFD.EMI. EXP.CALLE" CRNOMBRELARGO="C.F.D..EMI. EXP.CALLE" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EXCALLE&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4487" CRREPID="209" CRCAMID="1710" CRNOMBRE="CFD.EMI. EXP.NUMERO" CRNOMBRELARGO="C.F.D..EMI. EXP.NUMERO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EXNUMERO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4488" CRREPID="209" CRCAMID="1680" CRNOMBRE="CFD.EMI. EXP.INTERIOR" CRNOMBRELARGO="C.F.D..EMI. EXP.INTERIOR" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EXINTERIOR&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4489" CRREPID="209" CRCAMID="1650" CRNOMBRE="CFD.EMI. EXP.COLONIA" CRNOMBRELARGO="C.F.D..EMI. EXP.COLONIA" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EXCOLONIA&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4490" CRREPID="209" CRCAMID="1660" CRNOMBRE="CFD.EMI. EXP.CP" CRNOMBRELARGO="C.F.D..EMI. EXP.CP" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;EXCP&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4491" CRREPID="209" CRCAMID="1420" CRNOMBRE="CFD.CFD SERIE FISCAL" CRNOMBRELARGO="C.F.D..CFD SERIE FISCA" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.SERIE" CRCODIGOSQL="RDOC1.SERIE" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4492" CRREPID="209" CRCAMID="1270" CRNOMBRE="CFD.CFD FOLIO" CRNOMBRELARGO="C.F.D..CFD FOLIO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.FOLIO" CRCODIGOSQL="RDOC1.FOLIO" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4493" CRREPID="209" CRCAMID="1260" CRNOMBRE="CFD.CFD FECHA" CRNOMBRELARGO="C.F.D..CFD FECHA" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;CFDFECHA&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4494" CRREPID="209" CRCAMID="1890" CRNOMBRE="CFD.REC.NOMBRE" CRNOMBRELARGO="C.F.D..REC.NOMBRE" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECNOMBRE&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4495" CRREPID="209" CRCAMID="1790" CRNOMBRE="CFD.REC. CALLE" CRNOMBRELARGO="C.F.D..REC. CALLE" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECCALLE&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4496" CRREPID="209" CRCAMID="1860" CRNOMBRE="CFD.REC. NUMERO" CRNOMBRELARGO="C.F.D..REC. NUMERO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECNUMERO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4497" CRREPID="209" CRCAMID="1830" CRNOMBRE="CFD.REC. INTERIOR" CRNOMBRELARGO="C.F.D..REC. INTERIOR" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECINTERIOR&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4498" CRREPID="209" CRCAMID="1800" CRNOMBRE="CFD.REC. COLONIA" CRNOMBRELARGO="C.F.D..REC. COLONIA" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECCOLONIA&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4499" CRREPID="209" CRCAMID="1810" CRNOMBRE="CFD.REC. CP" CRNOMBRELARGO="C.F.D..REC. CP" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECCP&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4500" CRREPID="209" CRCAMID="1840" CRNOMBRE="CFD.REC. LOCALIDAD" CRNOMBRELARGO="C.F.D..REC. LOCALIDAD" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECLOCALIDAD&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4501" CRREPID="209" CRCAMID="1820" CRNOMBRE="CFD.REC. ESTADO" CRNOMBRELARGO="C.F.D..REC. ESTADO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECESTADO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4502" CRREPID="209" CRCAMID="1900" CRNOMBRE="CFD.REC.RFC" CRNOMBRELARGO="C.F.D..REC.RFC" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECRFC&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4503" CRREPID="209" CRCAMID="1530" CRNOMBRE="CFD.CFDI UUID" CRNOMBRELARGO="C.F.D..CFDI UUID" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.UUID" CRCODIGOSQL="RDOC1.UUID" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4504" CRREPID="209" CRCAMID="1260" CRNOMBRE="CFD.CFD FECHA" CRNOMBRELARGO="C.F.D..CFD FECHA" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;CFDFECHA&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4505" CRREPID="209" CRCAMID="1200" CRNOMBRE="CFD.CFDI CERTIFICADO NUM. SAT" CRNOMBRELARGO="C.F.D..CFD CERTIFICADO NUM." CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;NCERTIFICADO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4506" CRREPID="209" CRCAMID="1370" CRNOMBRE="CFD.CFD METODO PAGO" CRNOMBRELARGO="C.F.D..CFD METODO PAGO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;METODOPAGO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4507" CRREPID="209" CRCAMID="1210" CRNOMBRE="CFD.CFD CUENTA PAGO" CRNOMBRELARGO="C.F.D..CFD CUENTA PAGO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;CTAPAGO&quot;)" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4508" CRREPID="209" CRCAMID="3320" CRNOMBRE="_DOCPAR.CANTIDAD" CRNOMBRELARGO="PARTIDAS.CANTIDAD" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID#" CRSQL="^.DESCANTIDAD*^.DESEQUIVALE" CRCODIGOSQL="RDOC3.DESCANTIDAD*RDOC3.DESEQUIVALE" CRPROCESO="" CRANCHO="12" CRDECIMAL="0" CRTIPO="N" CRALINEACION="D" CRFORMATO="3" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4509" CRREPID="209" CRCAMID="10300" CRNOMBRE="_DOCPAR.UNIDAD" CRNOMBRELARGO="PARTIDAS.UNIDAD.UNIDAD" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID# LEFT JOIN UNIDADES ON $.DESUNIID=^.UNIDADID#" CRSQL="^.UNIDAD" CRCODIGOSQL="RDOC31.UNIDAD" CRPROCESO="" CRANCHO="5" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4510" CRREPID="209" CRCAMID="6980" CRNOMBRE="_DOCPAR.CONCEPTO" CRNOMBRELARGO="PARTIDAS.ARTICULO.DESCRIPCION" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID# LEFT JOIN INV ON $.DESARTID=^.ARTICULOID#" CRSQL="^.DESCRIPCIO" CRCODIGOSQL="RDOC32.DESCRIPCIO" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4511" CRREPID="209" CRCAMID="6900" CRNOMBRE="_DOCPAR.CLAVE" CRNOMBRELARGO="PARTIDAS.ARTICULO.CLAVE" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID# LEFT JOIN INV ON $.DESARTID=^.ARTICULOID#" CRSQL="^.CLAVE" CRCODIGOSQL="RDOC32.CLAVE" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4512" CRREPID="209" CRCAMID="4080" CRNOMBRE="_DOCPAR.PRECIO UNITARIO" CRNOMBRELARGO="PARTIDAS.IMPORTES.PRECIO UNITARIO" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID# LEFT JOIN DESDOC ON $.DESID=^.DESID#" CRSQL="PORC(IF(^.DESDESCUENTO&lt;0,PORC(^.DESVENTA,-^.DESDESCUENTO,@DECPRE),^.DESVENTA),-LEAST(^.DESCUENTO,0),@DECPRE)/@PARIDAD" CRCODIGOSQL="PORC(IF(RDOC33.DESDESCUENTO&lt;0,PORC(RDOC33.DESVENTA,-RDOC33.DESDESCUENTO,@DECPRE),RDOC33.DESVENTA),-LEAST(RDOC33.DESCUENTO,0),@DECPRE)/@PARIDAD" CRPROCESO="" CRANCHO="14" CRDECIMAL="2" CRTIPO="N" CRALINEACION="D" CRFORMATO="2" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4513" CRREPID="209" CRCAMID="4930" CRNOMBRE="DOC.TOTAL LETRA" CRNOMBRELARGO="TOTAL" CRRELACION="" CRSQL="^.TOTAL/@PARIDAD" CRCODIGOSQL="DOC.TOTAL/@PARIDAD" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="N" CRALINEACION="" CRFORMATO="7" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4514" CRREPID="209" CRCAMID="4740" CRNOMBRE="DOC.IVA" CRNOMBRELARGO="IVA" CRRELACION="" CRSQL="IF(^.ESTADO=&quot;C&quot;,0,^.IMPUESTO/@PARIDAD)" CRCODIGOSQL="IF(DOC.ESTADO=&quot;C&quot;,0,DOC.IMPUESTO/@PARIDAD)" CRPROCESO="" CRANCHO="0" CRDECIMAL="2" CRTIPO="N" CRALINEACION="" CRFORMATO="2" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4515" CRREPID="209" CRCAMID="4760" CRNOMBRE="DOC.NOTA" CRNOMBRELARGO="NOTA" CRRELACION="" CRSQL="^.NOTA" CRCODIGOSQL="DOC.NOTA" CRPROCESO="" CRANCHO="0" CRDECIMAL="0" CRTIPO="C" CRALINEACION="" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4516" CRREPID="209" CRCAMID="1410" CRNOMBRE="CFD.CFD SELLO" CRNOMBRELARGO="C.F.D..CFD SELLO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;SELLO&quot;)" CRANCHO="60" CRDECIMAL="0" CRTIPO="C" CRALINEACION="Z" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4517" CRREPID="209" CRCAMID="1490" CRNOMBRE="CFD.CFDI SELLO SAT" CRNOMBRELARGO="C.F.D..CFDI CERTIFICADO SAT" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;NCERTIFICADOSAT&quot;)" CRANCHO="100" CRDECIMAL="0" CRTIPO="C" CRALINEACION="Z" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4518" CRREPID="209" CRCAMID="1180" CRNOMBRE="CFD.CFD CADENA" CRNOMBRELARGO="C.F.D..CFD CADENA" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CADENA" CRCODIGOSQL="RDOC1.CADENA" CRPROCESO="SEPARACADENA($$,140)" CRANCHO="300" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4519" CRREPID="209" CRCAMID="4850" CRNOMBRE="SUBTOTAL" CRNOMBRELARGO="SUBTOTAL" CRRELACION="" CRSQL="IF(^.ESTADO=&quot;C&quot;,0,(^.SUBTOTAL1+^.SUBTOTAL2)/@PARIDAD)" CRCODIGOSQL="IF(DOC.ESTADO=&quot;C&quot;,0,(DOC.SUBTOTAL1+DOC.SUBTOTAL2)/@PARIDAD)" CRPROCESO="" CRANCHO="14" CRDECIMAL="2" CRTIPO="N" CRALINEACION="D" CRFORMATO="2" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4520" CRREPID="209" CRCAMID="3980" CRNOMBRE="IMPORTE" CRNOMBRELARGO="PARTIDAS.IMPORTES.IMPORTE" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID# LEFT JOIN DESDOC ON $.DESID=^.DESID#" CRSQL="PORC(CALCULAIMPORTE(^.DESTIPO,^.DESCANTIDAD,^.DESDESCUENTO,^.DESVENTA,^.DESIEPSFC,^.DESIEPS,0,0),-LEAST(^.DESCUENTO,0),@DECPRE)" CRCODIGOSQL="PORC(CALCULAIMPORTE(RDOC33.DESTIPO,RDOC33.DESCANTIDAD,RDOC33.DESDESCUENTO,RDOC33.DESVENTA,RDOC33.DESIEPSFC,RDOC33.DESIEPS,0,0),-LEAST(RDOC33.DESCUENTO,0),@DECPRE)" CRPROCESO="" CRANCHO="14" CRDECIMAL="2" CRTIPO="N" CRALINEACION="D" CRFORMATO="2" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4521" CRREPID="209" CRCAMID="4930" CRNOMBRE="TOTAL" CRNOMBRELARGO="TOTAL" CRRELACION="" CRSQL="^.TOTAL/@PARIDAD" CRCODIGOSQL="DOC.TOTAL/@PARIDAD" CRPROCESO="" CRANCHO="14" CRDECIMAL="2" CRTIPO="N" CRALINEACION="D" CRFORMATO="2" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4522" CRREPID="209" CRCAMID="1400" CRNOMBRE="CFD_PNG_ARCHIVO" CRNOMBRELARGO="C.F.D..CFD PNG ARCHIVO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;ARCHIVOPNG&quot;)" CRANCHO="300" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4523" CRREPID="209" CRCAMID="1390" CRNOMBRE="CFD_PDF_ARCHIVO" CRNOMBRELARGO="C.F.D..CFD PDF ARCHIVO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;ARCHIVOPDF&quot;)" CRANCHO="300" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4524" CRREPID="209" CRCAMID="1190" CRNOMBRE="CFD_CERTIFICADO" CRNOMBRELARGO="C.F.D..CFD CERTIFICADO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;CERTIFICADO&quot;)" CRANCHO="20" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="4" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4525" CRREPID="209" CRCAMID="1740" CRNOMBRE="EMI._REGIMEN_FISCAL" CRNOMBRELARGO="C.F.D..EMI. REGIMEN FISCAL" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;REGIMEN&quot;)" CRANCHO="80" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4526" CRREPID="209" CRCAMID="1500" CRNOMBRE="CFDI_FECHA_TIMBRADO" CRNOMBRELARGO="C.F.D..CFDI FECHA TIMBRADO" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;FECHATIMBRADO&quot;)" CRANCHO="25" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4527" CRREPID="209" CRCAMID="4630" CRNOMBRE="FECHA1" CRNOMBRELARGO="FECHA" CRRELACION="" CRSQL="^.FECHA" CRCODIGOSQL="DOC.FECHA" CRPROCESO="" CRANCHO="10" CRDECIMAL="0" CRTIPO="D" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4528" CRREPID="209" CRCAMID="4680" CRNOMBRE="HORA" CRNOMBRELARGO="HORA" CRRELACION="" CRSQL="^.HORA" CRCODIGOSQL="DOC.HORA" CRPROCESO="" CRANCHO="8" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4529" CRREPID="209" CRCAMID="9620" CRNOMBRE="FECHAreimp" CRNOMBRELARGO="SISTEMA INFO.FECHA" CRRELACION="#" CRSQL="@FECSIS" CRCODIGOSQL="@FECSIS" CRPROCESO="" CRANCHO="10" CRDECIMAL="0" CRTIPO="D" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4530" CRREPID="209" CRCAMID="2160" CRNOMBRE="NUMERO" CRNOMBRELARGO="CLIENTE.NUMERO" CRRELACION="LEFT JOIN CLI ON $.CLIENTEID=^.CLIENTEID#" CRSQL="^.NUMERO" CRCODIGOSQL="RDOC2.NUMERO" CRPROCESO="" CRANCHO="5" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4531" CRREPID="209" CRCAMID="8810" CRNOMBRE="CLAVE" CRNOMBRELARGO="CLIENTE.AGENTE.CLAVE" CRRELACION="LEFT JOIN CLI ON $.CLIENTEID=^.CLIENTEID# LEFT JOIN PER ON $.VENDEDORID=^.PERID#" CRSQL="^.CATEGORIA" CRCODIGOSQL="RDOC21.CATEGORIA" CRPROCESO="" CRANCHO="2" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4532" CRREPID="209" CRCAMID="5020" CRNOMBRE="VENCIMIENTO" CRNOMBRELARGO="VENCIMIENTO" CRRELACION="" CRSQL="^.VENCE" CRCODIGOSQL="DOC.VENCE" CRPROCESO="" CRANCHO="10" CRDECIMAL="0" CRTIPO="D" CRALINEACION="I" CRFORMATO="2" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4533" CRREPID="209" CRCAMID="1840" CRNOMBRE="REC._LOCALIDAD" CRNOMBRELARGO="C.F.D..REC. LOCALIDAD" CRRELACION="LEFT JOIN CFD ON $.DOCID=^.DOCID AND ^.TIPDOC&lt;&gt;&quot;N&quot;#" CRSQL="^.CFDID" CRCODIGOSQL="RDOC1.CFDID" CRPROCESO="CAMCFD($$,&quot;RECLOCALIDAD&quot;)" CRANCHO="60" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4534" CRREPID="209" CRCAMID="4770" CRNOMBRE="NUMDC" CRNOMBRELARGO="NUMERO" CRRELACION="" CRSQL="^.NUMERO" CRCODIGOSQL="DOC.NUMERO" CRPROCESO="" CRANCHO="6" CRDECIMAL="0" CRTIPO="N" CRALINEACION="D" CRFORMATO="3" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4535" CRREPID="209" CRCAMID="2290" CRNOMBRE="TIPO" CRNOMBRELARGO="CLIENTE.TIPO" CRRELACION="LEFT JOIN CLI ON $.CLIENTEID=^.CLIENTEID#" CRSQL="IF(^.TIPO IN (&quot;R&quot;,&quot;D&quot;),&quot;CREDITO&quot;,&quot;CONTADO&quot;)" CRCODIGOSQL="IF(RDOC2.TIPO IN (&quot;R&quot;,&quot;D&quot;),&quot;CREDITO&quot;,&quot;CONTADO&quot;)" CRPROCESO="" CRANCHO="7" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4536" CRREPID="209" CRCAMID="10220" CRNOMBRE="TELEFONO" CRNOMBRELARGO="CLIENTE.DOMICILIO.TELEFONOS.TELEFONO" CRRELACION="LEFT JOIN CLI ON $.CLIENTEID=^.CLIENTEID# LEFT JOIN DOM ON $.DOMID=^.DOMID# LEFT JOIN TEL ON $.DOMID=^.DOMID#" CRSQL="^.TEL" CRCODIGOSQL="RDOC221.TEL" CRPROCESO="" CRANCHO="12" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4537" CRREPID="209" CRCAMID="6930" CRNOMBRE="CODIGO_FABRICAN" CRNOMBRELARGO="PARTIDAS.ARTICULO.CODIGO FABRICAN" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID# LEFT JOIN INV ON $.DESARTID=^.ARTICULOID#" CRSQL="^.CLVPROV" CRCODIGOSQL="RDOC32.CLVPROV" CRPROCESO="" CRANCHO="12" CRDECIMAL="0" CRTIPO="C" CRALINEACION="I" CRFORMATO="1" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="1" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4538" CRREPID="209" CRCAMID="3420" CRNOMBRE="DESCUENTO" CRNOMBRELARGO="PARTIDAS.DESCUENTO" CRRELACION="LEFT JOIN DES ON $.DOCID=^.DESDOCID#" CRSQL="^.DESDESCUENTO" CRCODIGOSQL="RDOC3.DESDESCUENTO" CRPROCESO="" CRANCHO="5" CRDECIMAL="2" CRTIPO="N" CRALINEACION="D" CRFORMATO="1" CRMULTIPLE="S" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="4539" CRREPID="209" CRCAMID="4770" CRNOMBRE="NUMERO1" CRNOMBRELARGO="NUMERO" CRRELACION="" CRSQL="^.NUMERO" CRCODIGOSQL="DOC.NUMERO" CRPROCESO="" CRANCHO="5" CRDECIMAL="0" CRTIPO="N" CRALINEACION="D" CRFORMATO="3" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
<CAMPO CRID="5044" CRREPID="209" CRCAMID="4710" CRNOMBRE="ID_DOCUMENTO" CRNOMBRELARGO="ID DOCUMENTO" CRRELACION="" CRSQL="^.DOCID" CRCODIGOSQL="DOC.DOCID" CRPROCESO="" CRANCHO="10" CRDECIMAL="0" CRTIPO="N" CRALINEACION="D" CRFORMATO="3" CRMULTIPLE="N" CROPERACION="" CRAGRUPADOR="0" CRORDEN="0" CRTIPOORDEN="A" PARAMETROSREL=""/>
</REPORTE>
</REPORTES>

