$(document).ready(()=>{

    let form = $("#form");

    form.form({

        fields: {
            username: {
                identifier  : 'username',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter your desired username'
                    },
                    {
                        type   : 'maxLength[20]',
                        prompt : 'Username too long. Max 20 characters'
                    }
                ]
            },
            birthdate: {
                identifier  : 'birthdate',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter your date of birth'
                    }
                ]
            },
            email: {
                identifier  : 'email',
                    rules: [
                        {
                            type   : 'empty',
                            prompt : 'Please enter your e-mail'
                        },
                        {
                            type    : 'email',
                            prompt  : "Please enter a valid e-mail"
                        }
                ]
            },
            password: {
                identifier  : 'password',
                    rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter a password'
                    }
                ]
            },
            passwordCheck: {
                identifier  : 'repeat-password',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter again your password'
                    },
                    {
                        type    : 'match[password]',
                        prompt : 'Passwords do not match'
                    }
                ]
            }
        }

    });

    form.submit((e)=>{

        if(form.form('is valid')){
            console.log("OKIDOKI");
        }else{
            e.preventDefault();
        }

    });

});