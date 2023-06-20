          <?php
              $doctype = "DNTT";
            // dd($payrequest->document_type->code);
              $doctype = $payrequest->document_type->code;
              $file_name = "payment.report.content_" . $doctype;

          ?>
            @if (count($payrequest->printedDocs) > 0)
                {!! $payrequest->printedDocs[count($payrequest->printedDocs)-1]->content !!}
            @else
                @if(View::exists($file_name)  )

                    @include($file_name)
                @else

                    <?php
                    $file_name = "payment.report.content_DNTT" ;
                    ?>

                    @include($file_name)
                @endif
            @endif
