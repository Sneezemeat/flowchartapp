import {Prop, Component} from 'vue-property-decorator'
import Vue from 'vue'

@Component
export default class GeometryElement extends Vue{
    @Prop() public x!: number;
    @Prop() public y!: number;
    @Prop() public width!: number;
    @Prop() public height!: number;
    @Prop() public colour!: number;
    @Prop() public scale!: number;
    @Prop() public text!: string;
}