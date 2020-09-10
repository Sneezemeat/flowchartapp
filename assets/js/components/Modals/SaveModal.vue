<template>
    <!-- Modal to enter name for saving diagram -->
    <b-modal width="500" id="save-modal" hide-header hide-footer>
        <h2>Diagramm speichern</h2>
        <p class="saveSubtitle">Unter welchem Namen soll das Diagramm gespeichert werden?</p>
        <b-row>
            <b-col :lg="12" :md="12" :sm="12" :xs="5">
                <validation-observer ref="observer" v-slot="{ handleSubmit, invalid }">
                    <b-form @submit.stop.prevent="handleSubmit($emit('save', name))">
                        <validation-provider name="Name" :rules="{min: 2,max: 30}"
                                             v-slot="validationContext">
                            <b-form-group label="Name" label-for="name-input">
                                <b-form-input class="nameInput" id="name-input" label="Name" outlined
                                              v-model="name"
                                              placeholder="Name" :state="getValidationState(validationContext)"
                                              aria-describedby="input-1-live-feedback"></b-form-input>
                                <b-form-invalid-feedback id="input-1-live-feedback"> {{validationContext.errors[0]}}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        <b-button class="button_outlined" type="submit" :disabled="invalid">
                            OK
                        </b-button>
                    </b-form>
                </validation-observer>
            </b-col>
        </b-row>
    </b-modal>
</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from 'vue-property-decorator'

    @Component
    export default class SaveModal extends Vue {
        @Prop() public saveName;
        private name = "";
        //for form validation
        getValidationState({dirty, validated, valid = null}) {
            return dirty || validated ? valid : null;
        }

        @Watch('saveName')
        onSaveNameChange(val: string){
            this.name = val;
        }
    }

</script>

<style scoped>
    .justify_main_bar {
        justify-content: left;
    }
    .tool_icon {
        border-left: 1px solid white;
        cursor: pointer;
    }

    .tool_icon:focus {
        outline: none;
    }

    .tool_icon_disabled {
        border-left: 1px solid white;
        cursor: default;
        color: lightgrey;
    }

    .tool_icon_disabled:focus {
        outline: none;
    }

    @media (max-width: 992px) {
        .main_bar {
            bottom: 0px;
            top:    auto;
        }

        .justify_main_bar {
            justify-content: center;
        }

        .tool_icon {
            border-left: none;
        }

        .tool_icon_disabled {
            border-left: none;
        }

    }

    @media (min-width: 992px) {
        .main_bar {
            top: 0px;
        }
    }
</style>