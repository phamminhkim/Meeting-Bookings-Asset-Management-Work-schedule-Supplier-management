<?php


             $doctype = "DGIA";
            // dd($document->document_type->code);
              $doctype = $document->document_type->code;
              $file_name = "managerprice.report.content_" . $doctype;
          ?>
            @if (count($document->printedDocs) > 0)
                {!! $document->printedDocs[count($document->printedDocs)-1]->content !!}
            @else
                @if(View::exists($file_name)  )

                    @include($file_name)
                @else

                    <?php
                    $file_name = "managerprice.report.content_DGIA" ;
                    ?>

                    @include($file_name)
                @endif
            @endif
