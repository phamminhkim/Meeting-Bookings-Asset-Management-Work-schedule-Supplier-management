<?php
              $doctype = "PDNS";
            // dd($document->document_type->code);
              $doctype = $document->document_type->code;
              $file_name = "document.report.content_" . $doctype;
          ?>
            @if(View::exists($file_name)  )
            
              @include($file_name)
             @else
             
              <?php
                $file_name = "document.report.content_PDNS" ;  
              ?>
               
              @include($file_name)
            @endif