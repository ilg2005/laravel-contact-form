<template>
    <div>

        <v-container>
            <h2 class="m-2">{{ msg }}</h2>

            <div v-if="errors">
                <div v-for="(field, k) in errors" :key="k"
                     class="error">
                    <p v-for="(error, j) in field" :key="j">
                        {{ error.toString().replace(/[]"/, '') }}
                    </p>
                </div>
            </div>

            <v-row class="info white--text"
                   no-gutters>
                <v-col class="pa-5"
                       cols="12"
                       md="6">
                    <v-sheet>
                        <v-form method="post"
                                ref="form"
                                v-model="valid"
                                lazy-validation
                        >

                            <v-text-field
                                label="Имя"
                                solo
                                flat
                                name="name"
                                id="name"
                                v-model.trim="fields.name"
                                :rules="rules.name"
                                required
                            />

                            <v-text-field
                                label="E-mail"
                                solo
                                flat
                                type="email"
                                name="email"
                                id="email"
                                v-model.trim="fields.email"
                                :rules="rules.email"
                                required
                            />

                            <v-text-field
                                label="Телефон"
                                solo
                                flat
                                name="phone"
                                id="phone"
                                v-model.trim="fields.phone"
                                :rules="rules.phone"
                                required
                            />

                            <v-text-field
                                label="Тема"
                                solo
                                flat
                                name="subject"
                                id="subject"
                                v-model.trim="fields.subject"
                                :rules="rules.subject"
                                required
                            />

                            <v-textarea
                                label="Сообщение"
                                solo
                                flat
                                name="message"
                                id="message"
                                rows="4"
                                v-model.trim="fields.message"
                                :rules="rules.message"
                                required
                            ></v-textarea>

                            <v-btn
                                block
                                type="submit"
                                :disabled="!valid || isSending"
                                @click.prevent="onFormSubmit"
                            >
                                {{ isSending ? 'Отправляется...' : 'Отправить' }}
                            </v-btn>

                            <Notification
                                v-if="notification.show"
                                :notification-msg="notification.msg"
                                :notification-type="notification.type"
                            />
                        </v-form>
                    </v-sheet>
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
            errors: [],
            isSending: false,
            fields: {
                name: '',
                email: '',
                phone: '',
                subject: '',
                message: '',
            },
            rules: {
                name: [
                    v => !!v || 'Имя обязательно',
                    v => (v && v.length <= 10) || 'Имя должно быть менее 10 символов',
                ],
                email: [
                    v => !!v || 'Нужно ввести E-mail',
                    v => /.+@.+\..+/.test(v) || 'Введите валидный E-mail',
                ],
                phone: [
                    v => !!v || 'Нужно ввести телефон',
                    v => /\d+/.test(v) || 'Только цифры!',
                    v => (v && v.length <= 11) || 'Не более 11 цифр',
                ],
                subject: [
                    v => !!v || 'Тема обязательна',
                ],
                message: [
                    v => !!v || 'Сообщение обязательно',
                    v => (v && v.length <= 500) || 'Не более 500 символов'
                ],
            },
            notification: {
                show: false,
                msg: '',
                type: ''
            },
        }
    },
    watch: {
        isSending: nv => nv,
    },
    methods: {
        onFormSubmit() {

            if (this.$refs.form.validate()) {
                this.isSending = true;
                const formData = new FormData();

                formData.append('name', this.fields.name);
                formData.append('email', this.fields.email);
                formData.append('phone', this.fields.phone);
                formData.append('subject', this.fields.subject);
                formData.append('message', this.fields.message);

                axios.post('/', formData, {
                    headers: {
                        "Access-Control-Allow-Origin": "*",
                    }
                }).then((_response) => {
                    this.notification.msg = 'Отправлено успешно!';
                    this.notification.type = 'success';
                    this.notification.show = true;
                    this.errors.length = 0;
                    this.$refs.form.reset();

                })
                    .catch((error) => {
                        if (error.response) {
                            this.notification.msg = `Ошибка ${error.response.status}: отправить не удалось!`;
                            this.notification.type = 'danger';
                            this.notification.show = true;
                            if (error.response.status === 422) {
                                this.errors.push(error.response.data.errors);
                            }
                            console.log(error.response.data);
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
