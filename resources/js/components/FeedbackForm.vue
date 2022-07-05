<template>
    <div>

        <v-container>
            <h1 class="text-center mb-3">{{ msg }}</h1>

            <v-row>
                <v-col cols="4" class="mx-auto">
                    <v-form method="post"
                            @submit.prevent="onFormSubmit"
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
                        <v-btn
                            block
                            type="submit"
                            :disabled="!valid || isSending"
                            @click="onFormSubmit"
                        >
                            {{ isSending ? 'Отправляется...' : 'Отправить' }}
                        </v-btn>

                        <Notification
                            v-if="notification.show"
                            :notification-msg="notification.msg"
                            :notification-type="notification.type"
                        />
                    </v-form>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>

import Notification from "./Notification";
export default {
    name: 'FeedbackForm',
    components: {Notification},
    props: {
        msg: String
    },
    data() {
        return {
            valid: true,
            isSending: false,
            notification: {
                show: false,
                msg: '',
                type: ''
            },
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
    watch: {
        isSending: nv => nv,
    },
    methods: {
        onFormSubmit() {

            if (this.$refs.form.validate()) {
                this.isSending = true;
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
                    this.notification.msg = 'Отправлено успешно!';
                    this.notification.type = 'success';
                    this.notification.show = true;
                    this.$refs.form.reset();

                })
                    .catch((error) => {
                        if (error.response) {
                            this.notification.msg = 'Ошибка! Отправить не удалось';
                            this.notification.type = 'danger';
                            console.log(error.response.data);
                            console.log(error.response.status);
                            console.log(error.response.headers);
                        }
                    })
                    .finally(() => {
                        this.isSending = false;
                        setTimeout(() => this.notification.show = false, 3000);
                    })

            }
        }
    }
}
</script>

<style scoped>

</style>
