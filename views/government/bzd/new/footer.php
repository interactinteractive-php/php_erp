</div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            totalProtocol<?php echo $this->uniqId ?>(<?php echo $this->id ?>, <?php echo $this->subjectId ?>);
        });
        
        function totalProtocol<?php echo $this->uniqId ?>(id,subjectId,duration, display, type) {
            setInterval(function () {
                            
                $.ajax({
                    type: 'post',
                    dataType: 'json',
                    url: 'conference/reviewTotalData',
                    data:{
                            id:id,
                            selectedId:subjectId,
                        },
                    success: function (data) {
                        let $approvedparticipant = data.data.attendance.approvedparticipant ? data.data.attendance.approvedparticipant : '0/0';
                        let $approvedpercent = data.data.attendance.approvedpercent ? data.data.attendance.approvedpercent : '0%';

                        let $declinedparticipant = data.data.attendance.declinedparticipant ? data.data.attendance.declinedparticipant : '0/0';
                        let $declinedpercent = data.data.attendance.declinedpercent ? data.data.attendance.declinedpercent : '0%';

                        let $manyTotal = data.data.attendance.attendance ? data.data.attendance.attendance : '00';
                        let $total = data.data.attendance.total ? data.data.attendance.total : '00';
                        let $finish = data.data.timer.endtime ? data.data.timer.endtime : '';

                        let $time = data.time[0] ? data.time[0] : '00';
                        let $time1 = data.time[1] ? data.time[1] : '00';
                        let $time2 = data.time[2] ? data.time[2] : '00';

                        if ($finish) {
                            $("#dialog-popupItem-"+id).modal("hide");
                            window.location.href = "/contentui/previewatten/" + subjectId;
                        }
                        var $html = "";
                        var $btml = "";
                        var $dhtml = "";
                        var $rhtml = "";
                        var $thtml = "";
                        $dhtml +='<span class="p-1 mr-2 text-secondary" style="font-size: 28px">' + $approvedparticipant + ' ' + $approvedpercent +' </span>';
                        $rhtml +='<span class="p-1 mr-2 text-secondary" style="font-size: 28px">' + $declinedparticipant + ' ' + $declinedpercent +' </span>';
                        $thtml +='<span class="px-2 py-1">' + $manyTotal + '</span> / <span class="px-2 py-1">' + $total + '</span>';

                        $.each(data.data.approved, function (index, row) {
                            $html +='<span style="font-size: 28px;" class="text-primary">' +row["fullname"] +'</span>';
                        });

                        $('#approved').empty().append($html).promise().done(function () {
                        });

                        $.each(data.data.declined, function (index, row) {
                            $btml +='<span style="font-size: 28px;" class="text-primary">' +row["fullname"] +'</span>';
                        });

                        $('#declined').empty().append($btml).promise().done(function () {
                        });

                        $('.approvedparticipant').empty().append($dhtml).promise().done(function () {
                        });

                        $('.declinedpercent').empty().append($rhtml).promise().done(function () {
                        });

                        $('.reviewTotal').empty().append($thtml).promise().done(function () {
                        });

                        $('.governmentReview_<?php echo $this->uniqId ?>').find('span[data-name="reviewMinit"]').text($time1);
                        $('.governmentReview_<?php echo $this->uniqId ?>').find('span[data-name="reviewSeconds"]').text($time2);
                    }
                }); 
            }, 1000);
        }

        function totalProtocolEnd<?php echo $this->uniqId ?>(elem, subjectid, mapid, meetingBookid) {
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'conference/reviewTotalEnd',
                data:{
                    subjectid:subjectid,
                    mapid:mapid,
                    meetingBookid:meetingBookid,
                    uniqid:<?php echo $this->uniqId ?>,
                },
                success: function (data) {
                    var $dialogName = "dialog-popupItem-" + subjectid;
                    $('#'+$dialogName).modal('hide');
                    setTimeout(() => {
                        window.location.href = "/contentui/previewatten/" + meetingBookid;
                    }, 1000);
                }
            });
        }

        function getDateTime() {
            var now     = new Date(); 
            var hour    = now.getHours();
            var minute  = now.getMinutes();
            var second  = now.getSeconds(); 
            
            if(hour.toString().length == 1) {
                hour = '0'+hour;
            }
            if(minute.toString().length == 1) {
                minute = '0'+minute;
            }
            if(second.toString().length == 1) {
                second = '0'+second;
            }   
 
            var dateTime = +hour+':'+minute;
            return dateTime;
        }
        
        function getDate() {
            var now     = new Date(); 
            var year    = now.getFullYear();
            var month   = now.getMonth()+1; 
            var day     = now.getDate();
         
            if (month.toString().length == 1) {
                month = '0'+month;
            }
            
            if (day.toString().length == 1) {
                day = '0' + day;
            }   
          
            var dateTime = year+'.'+month+'.'+day;   
            return dateTime;
        }
    </script>
    <!-- ?php echo isset($this->defaultJs) ? $this->defaultJs : '' ?> -->
</body>
</html>