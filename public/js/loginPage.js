$(document)
    .ready(function() {
        $('#form')
            .form({
                fields: {
                    username: {
                        identifier  : 'email',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'Please enter your Username or Email'
                            }
                        ]
                    },
                    password: {
                        identifier  : 'password',
                        rules: [
                            {
                                type   : 'empty',
                                prompt : 'Please enter your password'
                            }
                        ]
                    }
                }
            }
        );

        $('#form').submit((e)=>{

            if($('#form').form('is valid')){
                console.log("OKIDOKI");
            }else{
                e.preventDefault();
            }

        });
    })
;