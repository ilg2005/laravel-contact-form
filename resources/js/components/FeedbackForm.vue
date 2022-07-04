<template>
    <div>

        <v-container>
            <h1 class="text-center mb-3">{{ msg }}</h1>

            <v-row>
                <v-col cols="4" class="mx-auto">
                    <v-form method="post"
                            ref="form"
                            v-model="valid"
                            lazy-validation
                    >
                        <div class="form-group">
                            <label>Имя</label>
                            <v-text-field
                                solo
                                name="name"
                                id="name"
                                v-model="name"
                                :rules="nameRules"
                                required
                            />
                        </div>

                        <div class="form-group">
                            <label>E-mail</label>
                            <v-text-field
                                solo
                                type="email"
                                name="email"
                                id="email"
                                v-model="email"
                                :rules="emailRules"
                                required
                            />

                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <v-text-field
                                solo
                                name="phone"
                                id="phone"
                                v-model="phone"
                                :rules="phoneRules"
                                required
                            />
                        </div>
                        <div class="form-group">
                            <label>Тема</label>
                            <v-text-field
                                solo
                                name="subject"
                                id="subject"
                                v-model="subject"
                                :rules="subjectRules"
                                required
                            />
                            <div class="error">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Сообщение</label>
                            <v-textarea
                                solo
                                name="message"
                                id="message"
                                rows="4"
                                v-model="message"
                                :rules="messageRules"
                                required
                            ></v-textarea>
                            <div class="error">
                            </div>
                        </div>
                        <button type="submit"
                               class="btn btn-primary btn-block text-white"
                               @click="onFormSubmit"
                        >Отправить</button>
                    </v-form>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>

export default {
    name: 'FeedbackForm',
    props: {
        msg: String
    },
    data() {
        return {
            valid: true,
            name: '',
            nameRules: [
                v => !!v || 'Имя обязательно',
                v => (v && v.length <= 10) || 'Имя должно быть менее 10 символов',
            ],
            email: '',
            emailRules: [
                v => !!v || 'Нужно ввести E-mail',
                v => /.+@.+\..+/.test(v) || 'Введите валидный E-mail',
            ],
            phone: '',
            phoneRules: [
                v => !!v || 'Нужно ввести телефон',
                v => /\d+/.test(v) || 'Только цифры!',
                v => (v && v.length <= 11) || 'Не более 11 цифр',
            ],
            subject: '',
            subjectRules: [
                v => !!v || 'Тема обязательна',
            ],
            message: '',
            messageRules: [
                v => !!v || 'Сообщение обязательно',
                v => (v && v.length <= 500) || 'Не более 500 символов'
            ],
        }
    },
    methods: {
        onFormSubmit() {

            if (this.$refs.form.validate()) {
                let formData = new FormData();

                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('phone', this.phone);
                formData.append('subject', this.subject);
                formData.append('message', this.message);

                axios.post('/', formData, {
                    headers: {
                        "Access-Control-Allow-Origin": "*",
                    }
                }).then((response) => {
                    console.log(response.data);
                    this.$refs.form.reset();
                })
                    .catch((error) => {
                        if (error.response) {
                            console.log(error.response.data);
                            console.log(error.response.status);
                            console.log(error.response.headers);
                        }
                    })


            }
        }
    }
}
</script>

<style scoped>

</style>
