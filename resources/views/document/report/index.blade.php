<?php
              $doctype = "PDNS";
            // dd($document->document_type->code);
              $doctype = $document->document_type->code;
              $file_name = "document.report.content_" . $doctype;
          ?>
            @if (count($document->printedDocs) > 0)
                {!! $document->printedDocs[count($document->printedDocs)-1]->content !!}
            @else
                @if(View::exists($file_name)  )

                    @include($file_name)
                @else

                    <?php
                    $file_name = "document.report.content_PDNS" ;
                    ?>

                    @include($file_name)
                @endif
            @endif
