<?php
              $doctype = "PDNS";
            // dd($car->document_type->code);
              $doctype = $car->document_type->code;
              $file_name = "car.report.content_" . $doctype;
          ?>
            @if(View::exists($file_name)  )
            
              @include($file_name)
             @else
             
              <?php
                $file_name = "car.report.content_PDNS" ;  
              ?>
               
              @include($file_name)
            @endif