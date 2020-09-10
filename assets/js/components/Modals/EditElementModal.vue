<template>
    <!-- Modal to edit text in element -->
    <b-modal width="500" id="edit-modal" hide-header hide-footer v-if="activeElement">
        <b-row>
            <b-col :lg="12" :md="12" :sm="12" :xs="5">
                <validation-observer ref="observer" v-slot="{ handleSubmit, invalid }">
                    <b-form @submit.prevent="handleSubmit($emit('edit', text))">
                        <validation-provider name="Text" :rules="{max: (activeElement.type === 'LoopStart' || activeElement.type === 'LoopEnd') ? 15 : 25}"
                                             v-slot="validationContext">
                            <b-form-group label="Text" label-for="text-input">
                                <b-form-input class="nameInput" id="text-input" outlined v-if="activeElement"
                                              v-model="text"
                                              placeholder="Text"
                                              :state="getValidationState(validationContext)"
                                              aria-describedby="input-1-live-feedback"></b-form-input>
                                <b-form-invalid-feedback id="input-1-live-feedback"> {{validationContext.errors[0]}}
                                </b-form-invalid-feedback>
                            </b-form-group>
                        </validation-provider>
                        <section v-if="activeElement && activeElement.type === 'Condition'">
                            <section v-bind:key="'line_text_input_' + line.data.id"
                                     v-for="line in activeElement.lineOrigins">
                                <validation-provider
                                        :name="'Verbindungs-Text zum Element &quot;' + (line.data.targetElement !== null ? line.data.targetElement.data.text + '&quot;' : '')"
                                        :rules="{max: 20}"
                                        v-slot="validationContext">
                                    <b-form-group>
                                        <label :for="'line-text-input-' + line.data.id">
                                            <b-icon-arrow-right
                                                    v-if="line.data.originPosition === linePosition.Right"></b-icon-arrow-right>
                                            <b-icon-arrow-left
                                                    v-else-if="line.data.originPosition === linePosition.Left"></b-icon-arrow-left>
                                            <b-icon-arrow-up
                                                    v-else-if="line.data.originPosition === linePosition.Top"></b-icon-arrow-up>
                                            <b-icon-arrow-down
                                                    v-else-if="line.data.originPosition === linePosition.Bottom"></b-icon-arrow-down>
                                            {{line.data.targetElement.data.text}}
                                        </label>
                                        <b-form-input class="nameInput" :id="'line-text-input-' + line.data.id"
                                                      outlined
                                                      v-model="line.data.validationText"
                                                      placeholder="Verbindungs-Text"
                                                      :state="getValidationState(validationContext)"
                                                      aria-describedby="input-1-live-feedback"></b-form-input>
                                        <b-form-invalid-feedback id="input-1-live-feedback">
                                            {{validationContext.errors[0]}}
                                        </b-form-invalid-feedback>
                                    </b-form-group>
                                </validation-provider>
                            </section>
                        </section>
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
    import {LinePosition} from "../../DTO/LinePosition";

    @Component
    export default class EditElementModal extends Vue {
        @Prop() public editText;
        @Prop() public activeElement;
        private text = "";
        private linePosition = LinePosition;
        //for form validation
        getValidationState({dirty, validated, valid = null}) {
            return dirty || validated ? valid : null;
        }

        @Watch('editText')
        onSaveNameChange(val: string){
            this.text = val;
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