<template>
    <div>

      <v-dialog v-model="value">
        <v-card>
          <v-card-title>
            <span class="headline">Редактирование учебной недели</span>
          </v-card-title>
          <v-card-text>
            <!-- Поле для формы -->
            <v-form ref="form" v-model="formStudyWeek.valid" lazy-validation>
              <v-select
                v-model="formStudyWeek.select"
                :items="formStudyWeek.items"
                :rules="[v => { return v !== '' ||  v !== null }]"
                label="Тип недели"
                required
              ></v-select>
            </v-form>
          </v-card-text>

          <v-card-actions>
            <v-spacer></v-spacer>

            <v-btn color="success" class="mr-4" @click="closeDialog()">
              Сохранить
            </v-btn>
            <v-btn color="error" class="mr-4" @click="closeDialog()">
              Закрыть
            </v-btn>

          </v-card-actions>
        </v-card>
      </v-dialog>

    </div>
</template>

<script>
    export default {
      name: "StudyWeeksForm",
      props: ['value','blabla'],
      data: function () {
        return {
          // Валидация формы
          formStudyWeek: {
            valid: true,
            select: null,
            items: [
              {text: 'Четная', value: true},
              {text: 'Нечетная', value: false},
            ],
          }
        }
      },

      methods: {
        closeDialog(){
          this.$emit('close',{blabla:'ffe'});
        },
      },

      mounted() {
        console.log(this.blabla);
      }

    }
</script>

<style scoped>

</style>
