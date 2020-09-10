import axios, {AxiosResponse} from "axios";
import Component from "vue-class-component";
import {CoordiantesMixin} from "./CoordiantesMixin";
import {v4 as uuidv4} from 'uuid';

@Component
export class DiagramMixin extends CoordiantesMixin {

    constructor() {
        super();
    }

    addDiagram(name: string, data: string) {
        if (!JSON.parse(localStorage.local)) {
            const dataObject = {
                name: name,
                data: data,
            };
            return axios.post("/diagram/add", dataObject, {
                headers: {'Content-Type': 'application/json'},
                withCredentials: true
            });
        } else {
            const diagrams = localStorage.diagrams === "undefined" ? [] : JSON.parse(localStorage.diagrams);
            const diagram = {id: uuidv4(), data: data, name: name};
            diagrams.push(diagram);
            localStorage.diagrams = JSON.stringify(diagrams);
            return new Promise<AxiosResponse<any>>(function (resolve) {
                const data = {
                    status: 200,
                    statusText: "OK",
                    headers: [],
                    config: {},
                    data: diagram
                }
                resolve(data);
            });
        }
    }

    updateDiagram(id: string, name: string, data: string) {
        if (!JSON.parse(localStorage.local)) {
            const dataObject = {
                name: name,
                data: data,
            };
            return axios.put("/diagram/update/" + id, dataObject, {
                headers: {'Content-Type': 'application/json'},
                withCredentials: true
            });
        } else {
            const diagrams = localStorage.diagrams === "undefined" ? [] : JSON.parse(localStorage.diagrams);
            const diagram = diagrams.find(diagram => diagram.id === id);
            const index = diagrams.indexOf(diagram);
            diagrams[index] = {id: diagram.id, data: data, name: name};
            localStorage.diagrams = JSON.stringify(diagrams);
            return new Promise<AxiosResponse<any>>(function (resolve) {
                const data = {
                    status: 200,
                    statusText: "OK",
                    headers: [],
                    config: {},
                    data: diagrams[index]
                }
                resolve(data);
            })
        }
    }


    loadDiagram(id: string) {
        if (!JSON.parse(localStorage.local)) {
            return axios.get("/diagram/" + id, {withCredentials: true});
        }
        return new Promise<AxiosResponse<any>>(function (resolve) {
            const diagram = JSON.parse(localStorage.diagrams).find(diagram => diagram.id === id);
            const data = {
                status: 200,
                statusText: "OK",
                headers: [],
                config: {},
                data: diagram
            }
            resolve(data);
        })
    }

    getAllDiagrams(page: number) {
        if (!JSON.parse(localStorage.local)) {
            return axios.get("/diagram/all/" + page, {withCredentials: true});
        }
        return new Promise<AxiosResponse<any>>(function (resolve) {
            const diagrams = JSON.parse(localStorage.diagrams);
            const limit = 5;
            const data = {
                status: 200,
                statusText: "OK",
                headers: [],
                config: {},
                data: {
                    diagrams: diagrams.splice((page - 1) * limit, limit),
                    max: diagrams.length,
                    limit: limit
                }
            }
            resolve(data);
        })
    }

    deleteOneDiagram(id: string) {
        if (!JSON.parse(localStorage.local)) {
            const formData = new FormData();
            formData.set('_method', 'DELETE')
            return axios.post("/diagram/remove/" + id, formData, {withCredentials: true})
        } else {
            const diagrams = localStorage.diagrams === "undefined" ? [] : JSON.parse(localStorage.diagrams);
            const diagram = diagrams.find(diagram => diagram.id === id);
            const index = diagrams.indexOf(diagram);
            diagrams.splice(index, 1);
            localStorage.diagrams = JSON.stringify(diagrams);
            return new Promise<AxiosResponse<any>>(function (resolve) {
                resolve();
            })
        }
    }

    async syncDiagrams(diagrams) {
        for (const diagram of diagrams) {
            const dataObject = {
                id: diagram.id,
                name: diagram.name,
                data: diagram.data,
            };
            await axios.post("/diagram/sync", dataObject, {
                headers: {'Content-Type': 'application/json'},
                withCredentials: true
            });
        }
    }

}